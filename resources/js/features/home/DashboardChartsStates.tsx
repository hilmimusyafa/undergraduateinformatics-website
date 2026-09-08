import { Skeleton } from '@/components/ui/skeleton';

export function DashboardChartsSkeleton() {
    return (
        <div className="flex flex-col">
            <Skeleton className="h-8 w-72 max-w-full" />
            <div className="mt-5.5 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 md:mt-5 md:gap-6 lg:grid-cols-3 lg:gap-8">
                {Array.from({ length: 6 }, (_, index) => (
                    <div key={index} className="flex flex-col">
                        <Skeleton className="h-5 w-1/2" />
                        <Skeleton className="mt-4.5 aspect-[4/3] w-full md:mt-4" />
                    </div>
                ))}
            </div>
        </div>
    );
}
