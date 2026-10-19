<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);
    }

    private function admin(): User
    {
        return User::where('email', 'admin@majujaya.com')->firstOrFail();
    }

    private function customer(): User
    {
        return User::where('email', 'customer@majujaya.com')->firstOrFail();
    }

    // ------------------------------------------------------------------ access control

    public function test_all_seeded_products_have_distinct_local_images_and_fallback_works(): void
    {
        $products = Product::all();
        $this->assertCount(23, $products);

        $paths = [];
        foreach ($products as $product) {
            $this->assertNotNull($product->image, "Product {$product->name} is missing an image path.");
            $this->assertStringStartsWith('products/', $product->image);
            Storage::disk('public')->assertExists($product->image);
            $this->assertStringContainsString('/storage/' . $product->image, $product->image_url);
            $paths[] = $product->image;
        }

        // All 23 products must have distinct image files
        $this->assertCount(23, array_unique($paths));

        // Verify fallback when image is null or missing from disk
        $sample = $products->first();
        $sample->image = 'products/non-existent-file.jpg';
        $this->assertStringEndsWith('/images/default-product.svg', $sample->image_url);
    }

    public function test_public_pages_render_for_guests(): void
    {
        $product = Product::firstOrFail();

        $this->get('/')->assertOk()->assertSee('TB MAJU JAYA');
        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.index', ['q' => 'semen', 'sort' => 'termurah', 'category' => 'pipa-fitting']))->assertOk();
        $this->get(route('products.show', $product))->assertOk()->assertSee($product->name);
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    public function test_guests_are_redirected_to_login_from_protected_routes(): void
    {
        foreach (['cart.index', 'checkout.create', 'orders.index', 'profile.edit', 'admin.dashboard', 'admin.products.index'] as $name) {
            $this->get(route($name))->assertRedirect(route('login'));
        }
    }

    public function test_customers_cannot_open_admin_area(): void
    {
        $this->actingAs($this->customer());

        foreach (['admin.dashboard', 'admin.products.index', 'admin.categories.index', 'admin.orders.index', 'admin.customers.index'] as $name) {
            $this->get(route($name))->assertForbidden();
        }
    }

    public function test_login_redirects_by_role(): void
    {
        $this->post(route('login.attempt'), ['email' => 'admin@majujaya.com', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login.attempt'), ['email' => 'customer@majujaya.com', 'password' => 'password'])
            ->assertRedirect(route('home'));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->post(route('login.attempt'), ['email' => 'admin@majujaya.com', 'password' => 'nope'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_always_creates_a_customer_even_if_role_is_injected(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Sneaky', 'email' => 'sneaky@example.com', 'password' => 'password123',
            'password_confirmation' => 'password123', 'role' => 'admin',
        ])->assertRedirect(route('home'));

        $this->assertSame('customer', User::where('email', 'sneaky@example.com')->value('role'));
    }

    // ------------------------------------------------------------------ customer flow

    public function test_customer_pages_render(): void
    {
        $this->actingAs($this->customer());
        $order = Order::firstOrFail();

        $this->get(route('cart.index'))->assertOk();
        $this->get(route('orders.index'))->assertOk();
        foreach (['semua', 'menunggu', 'diproses', 'dikirim', 'selesai'] as $tab) {
            $this->get(route('orders.index', ['tab' => $tab]))->assertOk();
        }
        $this->get(route('orders.show', $order))->assertOk();
        $this->get(route('orders.invoice', $order))->assertOk()->assertSee('INVOICE');
        $this->get(route('profile.edit'))->assertOk();
    }

    public function test_full_cart_checkout_and_cancel_cycle_adjusts_stock(): void
    {
        $customer = $this->customer();
        $this->actingAs($customer);
        $product = Product::where('name', 'Semen Tiga Roda 50kg')->firstOrFail();
        $stockBefore = $product->stock;

        // add (twice -> quantity accumulates) via the same JSON path the live badge uses
        $this->postJson(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2])->assertOk()->assertJson(['ok' => true]);
        $this->postJson(route('cart.store'), ['product_id' => $product->id, 'quantity' => 3])->assertOk()->assertJson(['count' => 5]);

        $cart = Cart::where('user_id', $customer->id)->firstOrFail();
        $this->patch(route('cart.update', $cart->id), ['quantity' => 4])->assertRedirect();
        $this->assertSame(4, $cart->fresh()->quantity);

        $this->get(route('checkout.create'))->assertOk();
        $this->post(route('checkout.store'), [
            'recipient_name' => 'Budi', 'phone' => '081234567890', 'shipping_address' => 'Jl. Melati No. 5 Tangerang',
        ])->assertRedirect();

        $order = Order::where('user_id', $customer->id)->latest('id')->firstOrFail();
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(4 * $product->price, $order->total_amount);
        $this->assertSame($stockBefore - 4, $product->fresh()->stock);
        $this->assertSame(0, Cart::where('user_id', $customer->id)->count());

        $this->post(route('orders.proof', $order), ['payment_proof' => UploadedFile::fake()->image('bukti.jpg')])->assertSessionHasNoErrors();
        Storage::disk('public')->assertExists($order->fresh()->payment_proof);

        $this->post(route('orders.cancel', $order))->assertRedirect();
        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
        $this->assertSame($stockBefore, $product->fresh()->stock, 'Cancelling must restock.');
    }

    public function test_cannot_add_more_than_stock_or_out_of_stock_items(): void
    {
        $this->actingAs($this->customer());
        $product = Product::where('name', 'Lampu LED Philips 12W')->firstOrFail(); // stock 5

        $this->postJson(route('cart.store'), ['product_id' => $product->id, 'quantity' => 50])->assertOk();
        $this->assertSame(5, Cart::where('product_id', $product->id)->value('quantity'));

        $product->update(['stock' => 0]);
        $this->postJson(route('cart.store'), ['product_id' => $product->id])->assertStatus(422)->assertJson(['ok' => false]);
    }

    public function test_checkout_with_insufficient_stock_is_refused_and_creates_no_order(): void
    {
        $customer = $this->customer();
        $this->actingAs($customer);
        $product = Product::where('name', 'Lampu LED Philips 12W')->firstOrFail();
        Cart::create(['user_id' => $customer->id, 'product_id' => $product->id, 'quantity' => 3]);
        $product->update(['stock' => 1]);
        $ordersBefore = Order::count();

        $this->post(route('checkout.store'), [
            'recipient_name' => 'Budi', 'phone' => '081234567890', 'shipping_address' => 'Jl. Melati No. 5 Tangerang',
        ])->assertRedirect(route('cart.index'))->assertSessionHas('error');

        $this->assertSame($ordersBefore, Order::count());
        $this->assertSame(1, $product->fresh()->stock);
    }

    public function test_checkout_validation_errors(): void
    {
        $customer = $this->customer();
        $this->actingAs($customer);
        Cart::create(['user_id' => $customer->id, 'product_id' => Product::firstOrFail()->id, 'quantity' => 1]);

        $this->post(route('checkout.store'), ['recipient_name' => '', 'phone' => 'abc', 'shipping_address' => 'x'])
            ->assertSessionHasErrors(['recipient_name', 'phone', 'shipping_address']);
    }

    public function test_customer_cannot_see_or_touch_other_customers_orders_or_carts(): void
    {
        $other = User::factory()->create();
        $theirOrder = Order::create([
            'order_number' => 'TBMJ-OTHER-1', 'user_id' => $other->id, 'total_amount' => 1000, 'status' => 'pending',
            'recipient_name' => 'X', 'phone' => '0812345678', 'shipping_address' => 'Somewhere far away',
        ]);
        $theirCart = Cart::create(['user_id' => $other->id, 'product_id' => Product::firstOrFail()->id, 'quantity' => 1]);

        $this->actingAs($this->customer());
        $this->get(route('orders.show', $theirOrder))->assertNotFound();
        $this->get(route('orders.invoice', $theirOrder))->assertNotFound();
        $this->post(route('orders.cancel', $theirOrder))->assertNotFound();
        $this->delete(route('cart.destroy', $theirCart->id))->assertNotFound();
        $this->assertDatabaseHas('carts', ['id' => $theirCart->id]);
    }

    public function test_profile_update_and_password_change(): void
    {
        $this->actingAs($this->customer());

        $this->put(route('profile.update'), [
            'name' => 'Budi Baru', 'email' => 'customer@majujaya.com', 'phone' => '081200000000', 'address' => 'Alamat baru',
            'current_password' => 'password', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Budi Baru', $this->customer()->name);
        $this->put(route('profile.update'), ['name' => 'x', 'email' => 'customer@majujaya.com', 'current_password' => 'wrong', 'password' => 'abcdefgh1', 'password_confirmation' => 'abcdefgh1'])
            ->assertSessionHasErrors('current_password');
    }

    // ------------------------------------------------------------------ admin

    public function test_admin_pages_render(): void
    {
        $this->actingAs($this->admin());
        $order = Order::firstOrFail();
        $product = Product::firstOrFail();

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Total Penjualan');
        $this->get(route('admin.products.index', ['q' => 'semen', 'category' => $product->category_id, 'stock' => 'low']))->assertOk();
        $this->get(route('admin.products.create'))->assertOk();
        $this->get(route('admin.products.edit', $product))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.orders.index'))->assertOk();
        $this->get(route('admin.orders.index', ['status' => 'pending', 'q' => 'TBMJ']))->assertOk();
        $this->get(route('admin.orders.show', $order))->assertOk();
        $this->get(route('admin.customers.index', ['q' => 'budi']))->assertOk();
    }

    public function test_admin_product_crud_with_image_lifecycle(): void
    {
        $this->actingAs($this->admin());
        $category = Category::firstOrFail();

        $this->post(route('admin.products.store'), [
            'name' => 'Produk Uji', 'category_id' => $category->id, 'price' => 12345, 'stock' => 7, 'unit' => 'pcs',
            'image' => UploadedFile::fake()->image('a.jpg', 300, 300),
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Produk Uji')->firstOrFail();
        $this->assertSame('produk-uji', $product->slug);
        $firstImage = $product->image;
        Storage::disk('public')->assertExists($firstImage);

        // duplicate name gets a unique slug
        $this->post(route('admin.products.store'), ['name' => 'Produk Uji', 'category_id' => $category->id, 'price' => 1, 'stock' => 1, 'unit' => 'pcs']);
        $this->assertSame('produk-uji-2', Product::where('name', 'Produk Uji')->latest('id')->value('slug'));

        // replacing the image deletes the old file
        $this->put(route('admin.products.update', $product), [
            'name' => 'Produk Uji', 'category_id' => $category->id, 'price' => 20000, 'stock' => 7, 'unit' => 'pcs',
            'image' => UploadedFile::fake()->image('b.jpg'),
        ])->assertRedirect();
        Storage::disk('public')->assertMissing($firstImage);
        Storage::disk('public')->assertExists($product->fresh()->image);

        // removing it clears the column
        $secondImage = $product->fresh()->image;
        $this->put(route('admin.products.update', $product), [
            'name' => 'Produk Uji', 'category_id' => $category->id, 'price' => 20000, 'stock' => 7, 'unit' => 'pcs', 'remove_image' => 1,
        ]);
        Storage::disk('public')->assertMissing($secondImage);
        $this->assertNull($product->fresh()->image);
        $this->get(route('products.show', $product))->assertOk(); // falls back to the placeholder

        // deleting works even when historical orders reference the product
        $this->delete(route('admin.products.destroy', $product))->assertRedirect();
        $this->assertModelMissing($product);
    }

    public function test_admin_product_validation(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.products.store'), ['name' => '', 'price' => -5, 'stock' => 'x', 'category_id' => 999])
            ->assertSessionHasErrors(['name', 'price', 'stock', 'category_id', 'unit']);

        $this->post(route('admin.products.store'), [
            'name' => 'Bad', 'category_id' => Category::firstOrFail()->id, 'price' => 1, 'stock' => 1, 'unit' => 'pcs',
            'image' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('image');
    }

    public function test_deleting_a_deleted_products_order_history_survives(): void
    {
        $this->actingAs($this->admin());
        $item = Order::firstOrFail()->items()->firstOrFail();
        $name = $item->product_name;

        $this->delete(route('admin.products.destroy', $item->product_id))->assertRedirect();

        $this->assertNull($item->fresh()->product_id);
        $this->get(route('admin.orders.show', $item->order))->assertOk()->assertSee($name);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin());
        $category = Category::has('products')->firstOrFail();

        $this->delete(route('admin.categories.destroy', $category))->assertSessionHas('error');
        $this->assertModelExists($category);

        $this->post(route('admin.categories.store'), ['name' => 'Baru', 'icon' => 'bi-tools'])->assertSessionHasNoErrors();
        $this->delete(route('admin.categories.destroy', Category::where('name', 'Baru')->firstOrFail()))->assertSessionHas('success');
    }

    public function test_admin_order_status_workflow_and_cancel_restocks(): void
    {
        $this->actingAs($this->admin());
        $order = Order::where('status', Order::STATUS_PENDING)->firstOrFail();
        $item = $order->items()->firstOrFail();
        $stock = $item->product->stock;

        foreach (['paid', 'processing', 'shipped', 'completed'] as $status) {
            $this->patch(route('admin.orders.status', $order), ['status' => $status])->assertSessionHasNoErrors();
            $this->assertSame($status, $order->fresh()->status);
        }
        $this->assertNotNull($order->fresh()->paid_at);

        $this->patch(route('admin.orders.status', $order), ['status' => 'bogus'])->assertSessionHasErrors('status');

        $this->patch(route('admin.orders.status', $order), ['status' => 'cancelled']);
        $this->assertSame($stock + $item->quantity, $item->product->fresh()->stock);

        // cancelled is final and cancelling twice must not double-restock
        $this->patch(route('admin.orders.status', $order), ['status' => 'paid'])->assertSessionHas('error');
        $order->fresh()->cancelAndRestock();
        $this->assertSame($stock + $item->quantity, $item->product->fresh()->stock);
    }

    public function test_legacy_history_pages_still_work(): void
    {
        $this->get(route('history'))->assertOk();
        $this->get(route('histories.last', ['action' => 'action1']))->assertOk();
    }
}
