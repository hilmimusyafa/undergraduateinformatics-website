import { Skeleton } from '@/components/ui/skeleton';

export function LinkCardSkeleton() {
    return (
        <div
            role="status"
            aria-label="Memuat kartu tautan"
            className="not-typeset flex flex-col gap-2"
        >
            <Skeleton className="h-5 w-full" />
            <Skeleton className="h-5 w-full" />
            <Skeleton className="h-4 w-1/2" />
            <Skeleton className="h-4 w-1/2" />
        </div>
    );
}
