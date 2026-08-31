import './bootstrap';

// Standard interactive UI utilities for Laravel Blade
document.addEventListener('DOMContentLoaded', function () {
    // Auto dismiss alerts after 5 seconds if desired
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } else {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });

    // Copy to clipboard helper
    window.copyToClipboard = function (text, btnElement) {
        if (!navigator.clipboard) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showCopiedTooltip(btnElement);
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            showCopiedTooltip(btnElement);
        });
    };

    function showCopiedTooltip(btn) {
        if (!btn) return;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-success"></i> Disalin!';
        btn.classList.add('btn-copied');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-copied');
        }, 2000);
    }
});
