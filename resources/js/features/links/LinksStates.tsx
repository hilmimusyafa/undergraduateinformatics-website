import { ArticleContainer } from '@/components/ArticleContainer';
import { Skeleton } from '@/components/ui/skeleton';

import { LinksLayout } from './LinksLayout';
import { TableOfContentsSkeleton, skeletonSectionCount } from './TableOfContentsStates';

export function LinksSkeleton() {
    return (
        <LinksLayout
            role="status"
            aria-label="Memuat daftar tautan penting"
            mainContent={
                <>
                    <h1>
                        <Skeleton className="h-9 w-1/2" />
                    </h1>
                    <div className="flex flex-col gap-1">
                        <Skeleton className="h-7 w-full" />
                        <Skeleton className="h-7 w-full" />
                    </div>
                    <div className="mt-10 md:mt-9 lg:hidden">
                        <TableOfContentsSkeleton />
                    </div>
                    {Array.from({ length: skeletonSectionCount }, (_, sectionIndex) => (
                        <section key={sectionIndex}>
                            <h2>
                                <Skeleton className="h-7 w-1/2" />
                            </h2>
                            <ul className="list-none">
                                {[0, 1, 2, 3].map((linkIndex) => (
                                    <li key={linkIndex} className="relative">
                                        <Skeleton className="absolute top-2 -left-5 size-2" />
                                        <Skeleton className="h-7 w-full" />
                                    </li>
                                ))}
                            </ul>
                        </section>
                    ))}
                </>
            }
            asideContent={<TableOfContentsSkeleton />}
        />
    );
}

export function LinksError() {
    return (
        <ArticleContainer className="max-w-[37em]">
            <p role="alert" className="text-muted-foreground">
                Terjadi kesalahan saat memuat halaman. Silakan coba lagi.
            </p>
        </ArticleContainer>
    );
}
