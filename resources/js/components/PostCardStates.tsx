import { Skeleton } from '@/components/ui/skeleton';

export function PostCardSkeleton() {
    return (
        <div
            role="status"
            aria-label="Memuat kartu informasi"
            className="not-typeset flex flex-col gap-2"
        >
            <Skeleton className="h-6 w-full" />
            <Skeleton className="h-6 w-full" />
            <Skeleton className="h-6 w-full" />
            <Skeleton className="mt-1 h-5 w-1/2" />
            <Skeleton className="h-5 w-24" />
        </div>
    );
}
