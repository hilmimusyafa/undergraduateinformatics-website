import { Skeleton } from '@/components/ui/skeleton';

export const skeletonSectionCount = 12;

export function TableOfContentsSkeleton() {
    return (
        <div className="typeset typeset-article">
            <h3>
                <Skeleton className="h-7 w-1/2" />
            </h3>
            <ul className="border-border border-l pl-2">
                {Array.from({ length: skeletonSectionCount }, (_, sectionIndex) => (
                    <li key={sectionIndex} className="list-none">
                        <Skeleton className="h-6 w-full" />
                    </li>
                ))}
            </ul>
        </div>
    );
}
