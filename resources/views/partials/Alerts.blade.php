@if ($errors->any())
    <div class="alert alert-danger admin-toast" role="alert">
        <div class="admin-toast__content">
            <i class="fa-solid fa-circle-exclamation admin-toast__icon"></i>
            <div class="admin-toast__body">
                @if ($errors->count() > 1)
                    <ul class="admin-toast__list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="admin-toast__text">{{ $errors->first() }}</span>
                @endif
            </div>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success admin-toast" role="alert">
        <div class="admin-toast__content">
            <i class="fa-solid fa-circle-check admin-toast__icon"></i>
            <div class="admin-toast__body">
                <span class="admin-toast__text">{{ session('success') }}</span>
            </div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger admin-toast" role="alert">
        <div class="admin-toast__content">
            <i class="fa-solid fa-circle-xmark admin-toast__icon"></i>
            <div class="admin-toast__body">
                <span class="admin-toast__text">{{ session('error') }}</span>
            </div>
        </div>
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning admin-toast" role="alert">
        <div class="admin-toast__content">
            <i class="fa-solid fa-triangle-exclamation admin-toast__icon"></i>
            <div class="admin-toast__body">
                <span class="admin-toast__text">{{ session('warning') }}</span>
            </div>
        </div>
    </div>
@endif

@if ($errors->any() || session()->has('success') || session()->has('error') || session()->has('warning'))
    <script>
        window.setTimeout(() => {
            document.querySelectorAll('.admin-toast').forEach((toast) => {
                toast.classList.add('admin-toast--hidden');
                window.setTimeout(() => toast.remove(), 250);
            });
        }, 4000);
    </script>
@endif
