<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\History;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUsers();
        $categories = $this->seedCategories();
        $this->seedProducts($categories);
        $this->seedOrders();
        $this->seedLegacyHistory();
    }

    private function seedUsers(): void
    {
        // `role` is not mass assignable on purpose, so seeding uses forceFill.
        foreach ([
            ['admin@majujaya.com', 'Admin Maju Jaya', User::ROLE_ADMIN, '081234567890', 'Jl. Raya Maju Jaya No. 12, Tangerang'],
            ['customer@majujaya.com', 'Budi Santoso', User::ROLE_CUSTOMER, '081298765432', 'Jl. Melati No. 5, RT 02/RW 03, Tangerang'],
        ] as [$email, $name, $role, $phone, $address]) {
            User::firstOrNew(['email' => $email])
                ->forceFill(['name' => $name, 'password' => 'password', 'role' => $role, 'phone' => $phone, 'address' => $address])
                ->save();
        }
    }

    /** @return array<string, Category> keyed by slug */
    private function seedCategories(): array
    {
        $rows = [
            ['Pipa & Fitting', 'bi-droplet-half'],
            ['Semen & Adukan', 'bi-bricks'],
            ['Cat & Pelapis', 'bi-palette-fill'],
            ['Perkakas', 'bi-tools'],
            ['Listrik', 'bi-lightning-charge-fill'],
            ['Baja & Atap', 'bi-building'],
        ];

        $categories = [];
        foreach ($rows as [$name, $icon]) {
            $category = Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'icon' => $icon]);
            $categories[$category->slug] = $category;
        }

        return $categories;
    }

    private function seedProducts(array $categories): void
    {
        (new ProductSeeder())->run($categories);
    }

    private function seedOrders(): void
    {
        $customer = User::where('email', 'customer@majujaya.com')->first();

        if (! $customer || $customer->orders()->exists()) {
            return;
        }

        // [status, days ago, [[product name, qty], ...]]
        $samples = [
            [Order::STATUS_COMPLETED, 12, [['Semen Tiga Roda 50kg', 10], ['Paku Beton 5cm (1 Dus)', 3]]],
            [Order::STATUS_SHIPPED, 3, [['Cat Dulux Catylac Putih 5kg', 4]]],
            [Order::STATUS_PROCESSING, 2, [['Pipa Rucika AW 3/4 inch (4m)', 6], ['Elbow PVC 1/2 inch', 12]]],
            [Order::STATUS_PENDING, 0, [['Baja Ringan C75 0.75mm (6m)', 20], ['Reng Baja Ringan 0.45mm (6m)', 30]]],
        ];

        foreach ($samples as [$status, $daysAgo, $lines]) {
            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'user_id' => $customer->id,
                'total_amount' => 0,
                'status' => $status,
                'recipient_name' => $customer->name,
                'phone' => $customer->phone,
                'shipping_address' => $customer->address,
                'paid_at' => $status === Order::STATUS_PENDING ? null : now()->subDays($daysAgo),
            ]);

            $total = 0;
            foreach ($lines as [$name, $qty]) {
                $product = Product::where('name', $name)->firstOrFail();
                $subtotal = $product->price * $qty;
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $qty);
            }

            $order->forceFill([
                'total_amount' => $total,
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ])->save();
        }
    }

    /** Sample rows for the pre-existing /history page. */
    private function seedLegacyHistory(): void
    {
        foreach ([
            ['ITM001', 'Semen Tiga Roda 50kg', 10, 65000],
            ['ITM002', 'Cat Tembok Dulux Putih 5kg', 5, 125000],
            ['ITM003', 'Paku Beton 5cm (1 Dus)', 20, 25000],
            ['ITM004', 'Pipa PVC Rucika 3/4 inch', 15, 45000],
        ] as [$itemId, $name, $qty, $price]) {
            History::firstOrCreate(['itemID' => $itemId], ['name' => $name, 'quantitas' => $qty, 'price' => $price]);
        }
    }
}
