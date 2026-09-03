import { ArticleContainer } from '@/components/ArticleContainer';
import { TextButton } from '@/components/TextButton';
import { Skeleton } from '@/components/ui/skeleton';

interface MsFormSkeletonProps {
    questions: number;
}

export function MsFormSkeleton({ questions }: MsFormSkeletonProps) {
    return (
        <ArticleContainer role="status" aria-label="Memuat formulir">
            <h1>
                <Skeleton className="h-9 w-full" />
            </h1>
            <div>
                <Skeleton className="h-7 w-full" />
            </div>
            <h2>
                <Skeleton className="h-7 w-full" />
            </h2>
            <div>
                <Skeleton className="h-7 w-full" />
            </div>
            {Array.from({ length: questions }).map((_, index) => (
                <section key={index}>
                    <h3>
                        <Skeleton className="h-7 w-full" />
                    </h3>
                    <div>
                        <Skeleton className="h-16 w-full" />
                    </div>
                </section>
            ))}
            <div className="mt-10 flex items-center gap-2 md:mt-9">
                <Skeleton className="h-9 w-full" />
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
