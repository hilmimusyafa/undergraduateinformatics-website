@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-3 shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation fs-5 mt-1 text-danger"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-1">Terdapat beberapa kesalahan:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check fs-5 text-success"></i>
        <div class="flex-grow-1 fw-semibold">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-xmark fs-5 text-danger"></i>
        <div class="flex-grow-1 fw-semibold">
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
