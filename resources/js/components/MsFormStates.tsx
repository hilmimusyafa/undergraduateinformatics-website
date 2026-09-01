import { TextButton } from '@/components/TextButton';
import { Skeleton } from '@/components/ui/skeleton';

export function MsFormSkeleton() {
    return (
        <div
            role="status"
            aria-label="Memuat formulir"
            className="typeset typeset-article mx-auto w-full max-w-[37em] px-4 py-10 md:py-9"
        >
            <h1>
                <Skeleton className="h-9 w-2/3" />
            </h1>
            <div>
                <Skeleton className="h-7 w-full" />
            </div>
            <h2>
                <Skeleton className="h-7 w-1/2" />
            </h2>
            <div>
                <Skeleton className="h-7 w-3/4" />
            </div>
            {[0, 1].map((index) => (
                <section key={index}>
                    <h3>
                        <Skeleton className="h-7 w-1/3" />
                    </h3>
                    <div>
                        <Skeleton className="h-16 w-full" />
                    </div>
                </section>
            ))}
            <div className="mt-10 flex items-center gap-2 md:mt-9">
                <Skeleton className="h-9 w-24" />
            </div>
        </div>
    );
}

export function MsFormUnavailable() {
    return (
        <div className="typeset typeset-article mx-auto w-full max-w-[37em] px-4 py-10 md:py-9">
            <p role="status" className="text-muted-foreground">
                Formulir sedang tidak tersedia. Silakan coba beberapa saat lagi.
            </p>
        </div>
    );
}

export function MsFormError() {
    return (
        <div className="typeset typeset-article mx-auto w-full max-w-[37em] px-4 py-10 md:py-9">
            <p role="alert" className="text-muted-foreground">
                Terjadi kesalahan saat memuat formulir. Silakan coba lagi.
            </p>
        </div>
    );
}

interface MsFormSuccessProps {
    onReset: () => void;
}

export function MsFormSuccess({ onReset }: MsFormSuccessProps) {
    return (
        <div className="typeset typeset-article mx-auto w-full max-w-[37em] px-4 py-10 md:py-9">
            <h1>Terima kasih!</h1>
            <p className="text-muted-foreground">Formulir Anda telah berhasil dikirim.</p>
            <div className="mt-4.5 md:mt-4">
                <TextButton variant="underline" className="text-blue-600" onClick={onReset}>
                    Isi Formulir Lagi
                </TextButton>
            </div>
        </div>
    );
}
