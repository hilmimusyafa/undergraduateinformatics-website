import { ArticleContainer } from '@/components/ArticleContainer';
import { PostCardSkeleton } from '@/components/PostCardStates';
import { Skeleton } from '@/components/ui/skeleton';

export function SearchSkeleton() {
    return (
        <ArticleContainer role="status" aria-label="Memuat hasil pencarian">
            <div className="w-full px-0 py-1.5">
                <Skeleton className="h-7 w-full md:h-6" />
            </div>
            <p className="mt-5.5 md:mt-5">
                <Skeleton className="h-7 w-1/3 md:h-6" />
            </p>
            <div className="mt-5.5 flex flex-col gap-4 md:mt-5">
                {[0, 1, 2, 3, 4, 5, 6, 7, 8, 9].map((index) => (
                    <PostCardSkeleton key={index} />
                ))}
            </div>
            <div className="mt-5.5 grid grid-cols-[1fr_auto_1fr] items-center md:mt-5">
                <Skeleton className="h-7 w-17.5 justify-self-start md:h-6" />
                <Skeleton className="h-7 w-14 justify-self-center md:h-6" />
                <Skeleton className="h-7 w-13 justify-self-end md:h-6" />
            </div>
        </ArticleContainer>
    );
}
