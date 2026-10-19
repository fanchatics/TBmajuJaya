<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body { background: #f1f5f9; font-family: system-ui, 'Segoe UI', sans-serif; }
        .sheet { max-width: 800px; margin: 1.5rem auto; background: #fff; padding: 2.5rem; border-radius: .75rem; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
        .brand { font-weight: 800; letter-spacing: .05em; color: #0f2540; font-size: 1.5rem; }
        .accent { color: #d97706; }
        th { background: #0f2540 !important; color: #fff !important; font-weight: 600; font-size: .85rem; }
        @media print { body { background: #fff; } .sheet { box-shadow: none; margin: 0; padding: 0; max-width: none; } .no-print { display: none !important; } }
    </style>
</head>
<body>
<div class="sheet">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="brand">TB MAJU JAYA</div>
            <div class="small text-muted">{{ config('shop.address') }}<br>WhatsApp +{{ config('shop.whatsapp') }}</div>
        </div>
        <div class="text-end">
            <h2 class="h4 mb-1 accent fw-bold">INVOICE</h2>
            <div class="fw-semibold">{{ $order->order_number }}</div>
            <div class="small text-muted">{{ $order->created_at->translatedFormat('d F Y') }}</div>
            <span class="badge text-bg-{{ $order->status_color }} mt-1">{{ $order->status_label }}</span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <div class="small text-muted text-uppercase">Ditagihkan kepada</div>
            <div class="fw-semibold">{{ $order->recipient_name }}</div>
            <div class="small">{{ $order->phone }}</div>
            <div class="small">{{ $order->shipping_address }}</div>
        </div>
        <div class="col-6 text-end">
            <div class="small text-muted text-uppercase">Pembayaran</div>
            @foreach (config('shop.bank_accounts') as $bank)
                <div class="small">{{ $bank['bank'] }} <strong>{{ $bank['number'] }}</strong><br><span class="text-muted">a.n. {{ $bank['holder'] }}</span></div>
            @endforeach
        </div>
    </div>

    <table class="table align-middle">
        <thead><tr><th>#</th><th>Produk</th><th class="text-end">Harga</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-end">@rupiah($item->price) / {{ $item->unit }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-end">@rupiah($item->subtotal)</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="4" class="text-end">TOTAL</th><th class="text-end">@rupiah($order->total_amount)</th></tr>
        </tfoot>
    </table>

    <p class="small text-muted mt-4 mb-0">Terima kasih telah berbelanja di TB Maju Jaya. Simpan invoice ini sebagai bukti transaksi.</p>

    <div class="mt-4 no-print d-flex gap-2">
        <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>
</body>
</html>
