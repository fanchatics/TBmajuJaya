@if (session('success') || session('error') || session('warning') || $errors->any())
    <div class="container mt-3">
        @foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $color)
            @if (session($key))
                <div class="alert alert-{{ $color }} alert-dismissible fade show mb-2" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                <strong>Periksa kembali isian Anda:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
@endif
