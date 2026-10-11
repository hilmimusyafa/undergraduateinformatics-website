@if ($errors->any())
    <div class="alert alert-danger admin-toast" role="alert" style="position: fixed !important; top: 24px !important; right: 24px !important; z-index: 9999 !important; display: inline-flex !important; width: fit-content !important; max-width: min(480px, calc(100vw - 48px)) !important; background-color: #fef2f2 !important; border: 1px solid #fecaca !important; color: #991b1b !important; border-radius: 12px !important; padding: 12px 18px !important; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important; margin: 0 !important;">
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
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="(function(b){const t=b.closest('.admin-toast');t.style.transition='opacity .2s ease, transform .2s ease';t.style.opacity='0';t.style.transform='translateY(-8px) scale(0.96)';setTimeout(()=>t.remove(),220);})(this)" style="background: none; border: none; padding: 0; margin-left: 14px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success admin-toast" role="alert" style="position: fixed !important; top: 24px !important; right: 24px !important; z-index: 9999 !important; display: inline-flex !important; width: fit-content !important; max-width: min(480px, calc(100vw - 48px)) !important; background-color: #ecfdf5 !important; border: 1px solid #a7f3d0 !important; color: #065f46 !important; border-radius: 12px !important; padding: 12px 18px !important; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important; margin: 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-circle-check admin-toast__icon" style="color: #059669; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('success') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="(function(b){const t=b.closest('.admin-toast');t.style.transition='opacity .2s ease, transform .2s ease';t.style.opacity='0';t.style.transform='translateY(-8px) scale(0.96)';setTimeout(()=>t.remove(),220);})(this)" style="background: none; border: none; padding: 0; margin-left: 14px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger admin-toast" role="alert" style="position: fixed !important; top: 24px !important; right: 24px !important; z-index: 9999 !important; display: inline-flex !important; width: fit-content !important; max-width: min(480px, calc(100vw - 48px)) !important; background-color: #fef2f2 !important; border: 1px solid #fecaca !important; color: #991b1b !important; border-radius: 12px !important; padding: 12px 18px !important; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important; margin: 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-circle-xmark admin-toast__icon" style="color: #dc2626; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('error') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="(function(b){const t=b.closest('.admin-toast');t.style.transition='opacity .2s ease, transform .2s ease';t.style.opacity='0';t.style.transform='translateY(-8px) scale(0.96)';setTimeout(()=>t.remove(),220);})(this)" style="background: none; border: none; padding: 0; margin-left: 14px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning admin-toast" role="alert" style="position: fixed !important; top: 24px !important; right: 24px !important; z-index: 9999 !important; display: inline-flex !important; width: fit-content !important; max-width: min(480px, calc(100vw - 48px)) !important; background-color: #fffbeb !important; border: 1px solid #fde68a !important; color: #92400e !important; border-radius: 12px !important; padding: 12px 18px !important; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important; margin: 0 !important;">
        <div class="admin-toast__content" style="display: inline-flex; align-items: flex-start; gap: 10px; line-height: 1.45; width: 100%;">
            <i class="fa-solid fa-triangle-exclamation admin-toast__icon" style="color: #d97706; font-size: 1.05rem; margin-top: 2px; flex-shrink: 0;"></i>
            <div class="admin-toast__body" style="flex: 1; min-width: 0; font-size: 0.88rem; font-weight: 500; word-break: break-word;">
                <span class="admin-toast__text" style="display: block; white-space: normal; word-break: break-word;">{{ session('warning') }}</span>
            </div>
            <button type="button" class="admin-toast__close" aria-label="Tutup" onclick="(function(b){const t=b.closest('.admin-toast');t.style.transition='opacity .2s ease, transform .2s ease';t.style.opacity='0';t.style.transform='translateY(-8px) scale(0.96)';setTimeout(()=>t.remove(),220);})(this)" style="background: none; border: none; padding: 0; margin-left: 14px; color: currentColor; opacity: 0.55; cursor: pointer; font-size: 0.95rem; line-height: 1; align-self: flex-start; margin-top: 3px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if ($errors->any() || session()->has('success') || session()->has('error') || session()->has('warning'))
    <script>
        window.setTimeout(() => {
            document.querySelectorAll('.admin-toast').forEach((toast) => {
                toast.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-12px) scale(0.96)';
                window.setTimeout(() => toast.remove(), 360);
            });
        }, 4500);
    </script>
@endif
