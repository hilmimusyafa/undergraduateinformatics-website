import { ArticleContainer } from '@/components/ArticleContainer';
import { Skeleton } from '@/components/ui/skeleton';

import { FieldGroup } from '../../components/ui/field';

export function ReservationSkeleton() {
    return (
        <ArticleContainer role="status" aria-label="Memuat formulir reservasi">
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
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
                <Skeleton className="h-7 w-full" />
            </div>
            <h2>
                <Skeleton className="h-7 w-full" />
            </h2>
            <section>
                <h3>
                    <Skeleton className="h-7 w-full" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-24 w-full" />
                </FieldGroup>
            </section>
            <section>
                <h3 className="flex flex-col gap-1">
                    <Skeleton className="h-7 w-full" />
                    <Skeleton className="h-7 w-full" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-24 w-full" />
                </FieldGroup>
            </section>
            <section>
                <h3>
                    <Skeleton className="h-7 w-full" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-24 w-full" />
                </FieldGroup>
            </section>
            <section>
                <h3>
                    <Skeleton className="h-7 w-full" />
                </h3>
                <FieldGroup>
                    <Skeleton className="h-24 w-full" />
                </FieldGroup>
            </section>
            <div className="mt-[39.375px] flex items-center gap-2 md:mt-8.75">
                <Skeleton className="h-9 flex-1 md:w-24 md:flex-none" />
            </div>
        </ArticleContainer>
    );
}
