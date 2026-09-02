import { ArticleContainer } from '@/components/ArticleContainer';
import { TextButton } from '@/components/TextButton';
import { Skeleton } from '@/components/ui/skeleton';

export function MsFormSkeleton() {
    return (
        <ArticleContainer role="status" aria-label="Memuat formulir">
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
        </ArticleContainer>
    );
}

export function MsFormUnavailable() {
    return (
        <ArticleContainer>
            <p role="status" className="text-muted-foreground">
                Formulir sedang tidak tersedia. Silakan coba beberapa saat lagi.
            </p>
        </ArticleContainer>
    );
}

export function MsFormError() {
    return (
        <ArticleContainer>
            <p role="alert" className="text-muted-foreground">
                Terjadi kesalahan saat memuat formulir. Silakan coba lagi.
            </p>
        </ArticleContainer>
    );
}

interface MsFormSuccessProps {
    onReset: () => void;
}

export function MsFormSuccess({ onReset }: MsFormSuccessProps) {
    return (
        <ArticleContainer>
            <h1>Terima kasih!</h1>
            <p className="text-muted-foreground">Formulir Anda telah berhasil dikirim.</p>
            <div className="mt-4.5 md:mt-4">
                <TextButton variant="underline" className="text-blue-600" onClick={onReset}>
                    Isi Formulir Lagi
                </TextButton>
            </div>
        </ArticleContainer>
    );
}
