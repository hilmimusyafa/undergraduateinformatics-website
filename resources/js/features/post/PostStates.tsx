import { ArticleContainer } from '@/components/ArticleContainer';
import { Skeleton } from '@/components/ui/skeleton';

export function PostSkeleton() {
    return (
        <div
            role="status"
            aria-label="Memuat detail informasi"
            className="mx-auto w-full max-w-[37em] py-10 md:py-9"
        >
            <article className="typeset typeset-article">
                <h1>
                    <Skeleton className="h-9 w-full" />
                </h1>
                <h2 className="mt-2">
                    <Skeleton className="h-7 w-full" />
                </h2>
                <div className="mt-8">
                    <Skeleton className="h-[37em] w-full" />
                </div>
                <div className="mt-12 space-y-2">
                    <Skeleton className="h-5 w-full" />
                    <Skeleton className="h-5 w-full" />
                    <Skeleton className="h-5 w-full" />
                </div>
                <div className="mt-12 flex flex-wrap items-center gap-2">
                    <Skeleton className="h-5 w-1/2" />
                </div>
            </article>
        </div>
    );
}

export function PostNotFound() {
    return (
        <ArticleContainer>
            <h1>Informasi tidak ditemukan</h1>
            <p className="text-muted-foreground">
                Informasi mungkin sudah dihapus atau alamatnya salah.
            </p>
        </ArticleContainer>
    );
}
