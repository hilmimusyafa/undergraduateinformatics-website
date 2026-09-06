@if ($errors->any())
    <div class="alert alert-danger admin-toast" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success admin-toast" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger admin-toast" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any() || session()->has('success') || session()->has('error'))
    <script>
        window.setTimeout(() => {
            document.querySelectorAll('.admin-toast').forEach((toast) => {
                toast.classList.add('admin-toast--hidden');
                window.setTimeout(() => toast.remove(), 250);
            });
        }, 4000);
    </script>
@endif
