import { ArticleContainer } from '@/components/ArticleContainer';
import { Skeleton } from '@/components/ui/skeleton';

export function TagListSkeleton() {
    return (
        <ArticleContainer role="status" aria-label="Memuat daftar topik">
            <h1>
                <Skeleton className="h-9 w-1/2" />
            </h1>
            <div className="flex flex-col gap-1">
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
            </div>
            <ul className="list-none">
                {[0, 1, 2, 3, 4, 5, 6].map((index) => (
                    <li key={index} className="relative">
                        <Skeleton className="absolute top-2 -left-5 size-2" />
                        <Skeleton className="h-7 w-1/2" />
                        <div>
                            <Skeleton className="h-7 w-full" />
                        </div>
                    </li>
                ))}
            </ul>
        </ArticleContainer>
    );
}
