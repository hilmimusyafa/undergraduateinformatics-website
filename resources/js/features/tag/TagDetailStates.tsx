import { MainAsideLayout } from '@/components/MainAsideLayout';
import { PostCardSkeleton } from '@/components/PostCardStates';
import { TableOfContentsSkeleton } from '@/components/TableOfContentsStates';
import { Skeleton } from '@/components/ui/skeleton';

export function TagDetailSkeleton() {
    return (
        <MainAsideLayout
            role="status"
            aria-label="Memuat detail topik"
            mainContent={
                <>
                    <h1>
                        <Skeleton className="h-9 w-1/2" />
                    </h1>
                    <p>
                        <Skeleton className="h-7 w-full" />
                    </p>
                    <div className="mt-10 md:mt-9 lg:hidden">
                        <TableOfContentsSkeleton />
                    </div>
                    {[0, 1, 2, 3, 4].map((sectionIndex) => (
                        <section
                            key={sectionIndex}
                            className={
                                sectionIndex > 0 ? 'mt-[calc(var(--typeset-flow)*1.4)]' : undefined
                            }
                        >
                            <h2>
                                <Skeleton className="h-7 w-1/2" />
                            </h2>
                            <div className="mt-[var(--typeset-flow)] flex flex-col gap-4">
                                <PostCardSkeleton />
                                <PostCardSkeleton />
                            </div>
                        </section>
                    ))}
                </>
            }
            asideContent={<TableOfContentsSkeleton />}
        />
    );
}
