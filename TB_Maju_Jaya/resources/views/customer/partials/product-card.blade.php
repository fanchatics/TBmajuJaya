<div class="product-card">
    <a href="{{ route('products.show', $product) }}" class="thumb">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        @if (! $product->in_stock)
            <span class="badge bg-secondary stock-badge">Stok Habis</span>
        @elseif ($product->is_low_stock)
            <span class="badge bg-warning text-dark stock-badge">Stok Menipis</span>
        @else
            <span class="badge bg-success stock-badge">Tersedia</span>
        @endif
    </a>
    <div class="p-3 d-flex flex-column flex-grow-1">
        <small class="text-muted mb-1">{{ $product->category->name }}</small>
        <a href="{{ route('products.show', $product) }}" class="name mb-2">{{ $product->name }}</a>
        <div class="price fs-5 mb-3 mt-auto">@rupiah($product->price) <small>/ {{ $product->unit }}</small></div>

        <form method="post" action="{{ route('cart.store') }}" data-cart-form>
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-accent w-100" @disabled(! $product->in_stock)>
                <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
            </button>
        </form>
    </div>
</div>
