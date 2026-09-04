import { ArticleContainer } from '@/components/ArticleContainer';
import { TextButton } from '@/components/TextButton';
import { Skeleton } from '@/components/ui/skeleton';

import { FieldGroup } from './ui/field';

export function MsFormSkeleton() {
    return (
        <ArticleContainer role="status" aria-label="Memuat formulir" className="max-w-[37em]">
            <h1>
                <div className="flex flex-col gap-1">
                    <Skeleton className="h-9 w-full" />
                    <Skeleton className="h-9 w-full" />
                    <Skeleton className="h-9 w-full" />
                </div>
            </h1>
            <div className="flex flex-col gap-1">
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
            </div>
            <h2>
                <Skeleton className="h-7 w-full" />
            </h2>
            <div className="flex flex-col gap-1">
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
            </div>
            <section>
                <h3>
                    <Skeleton className="h-7 w-1/2" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-7 w-1/2" />
                    <Skeleton className="h-16 w-full" />
                </FieldGroup>
            </section>
            <section>
                <h3>
                    <Skeleton className="h-7 w-1/2" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-7 w-1/2" />
                    <Skeleton className="h-16 w-full" />
                </FieldGroup>
            </section>
            <div className="mt-10 flex items-center gap-2 md:mt-9">
                <Skeleton className="h-9 flex-1 md:w-24 md:flex-none" />
            </div>
        </ArticleContainer>
    );
}

export function MsFormUnavailable() {
    return (
        <ArticleContainer className="max-w-[37em]">
            <p role="status" className="text-muted-foreground">
                Formulir sedang tidak tersedia. Silakan coba beberapa saat lagi.
            </p>
        </ArticleContainer>
    );
}

export function MsFormError() {
    return (
        <ArticleContainer className="max-w-[37em]">
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
        <ArticleContainer className="max-w-[37em]">
            <h1>Terima kasih!</h1>
            <p className="text-muted-foreground">Formulir Anda telah berhasil dikirim.</p>
            <div className="mt-4.5 md:mt-4">
                <TextButton variant="underline" onClick={onReset}>
                    Isi Formulir Lagi
                </TextButton>
            </div>
        </ArticleContainer>
    );
}
