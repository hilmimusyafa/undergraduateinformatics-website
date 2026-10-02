@if ($errors->any())
    <div class="alert alert-danger admin-toast" role="alert" style="display: inline-flex !important; width: fit-content !important; max-width: min(560px, 100%) !important; align-self: flex-start !important; justify-self: start !important; background-color: #fef2f2 !important; border: 1px solid #fecaca !important; color: #991b1b !important; border-radius: 12px !important; padding: 10px 16px !important; box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06) !important; margin: 4px 0 20px 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-circle-exclamation admin-toast__icon" style="color: #dc2626; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                @if ($errors->count() > 1)
                    <ul class="admin-toast__list" style="margin: 0; padding-left: 18px; display: flex; flex-direction: column; gap: 4px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ $errors->first() }}</span>
                @endif
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="this.closest('.admin-toast').remove()" style="background: none; border: none; padding: 0; margin-left: 12px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success admin-toast" role="alert" style="display: inline-flex !important; width: fit-content !important; max-width: min(560px, 100%) !important; align-self: flex-start !important; justify-self: start !important; background-color: #ecfdf5 !important; border: 1px solid #a7f3d0 !important; color: #065f46 !important; border-radius: 12px !important; padding: 10px 16px !important; box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06) !important; margin: 4px 0 20px 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-circle-check admin-toast__icon" style="color: #059669; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('success') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="this.closest('.admin-toast').remove()" style="background: none; border: none; padding: 0; margin-left: 12px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger admin-toast" role="alert" style="display: inline-flex !important; width: fit-content !important; max-width: min(560px, 100%) !important; align-self: flex-start !important; justify-self: start !important; background-color: #fef2f2 !important; border: 1px solid #fecaca !important; color: #991b1b !important; border-radius: 12px !important; padding: 10px 16px !important; box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06) !important; margin: 4px 0 20px 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-circle-xmark admin-toast__icon" style="color: #dc2626; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('error') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="this.closest('.admin-toast').remove()" style="background: none; border: none; padding: 0; margin-left: 12px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning admin-toast" role="alert" style="display: inline-flex !important; width: fit-content !important; max-width: min(560px, 100%) !important; align-self: flex-start !important; justify-self: start !important; background-color: #fffbeb !important; border: 1px solid #fde68a !important; color: #92400e !important; border-radius: 12px !important; padding: 10px 16px !important; box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06) !important; margin: 4px 0 20px 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-triangle-exclamation admin-toast__icon" style="color: #d97706; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('warning') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="this.closest('.admin-toast').remove()" style="background: none; border: none; padding: 0; margin-left: 12px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if ($errors->any() || session()->has('success') || session()->has('error') || session()->has('warning'))
    <script>
        window.setTimeout(() => {
            document.querySelectorAll('.admin-toast').forEach((toast) => {
                toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-6px)';
                window.setTimeout(() => toast.remove(), 320);
            });
        }, 4500);
    </script>
@endif
