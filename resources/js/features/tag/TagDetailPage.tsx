import { ErrorState } from '@/components/ErrorState';
import { isNotFoundError } from '@/lib/errors';

import { TagDetailContent } from './TagDetailContent';
import { TagDetailSkeleton, TagNotFound } from './TagDetailStates';
import { useTagDetail } from './useTagDetail';

export function TagDetailPage({ slug }: { slug: string }) {
    const query = useTagDetail(slug);

    if (query.isPending) {
        return <TagDetailSkeleton />;
    }

    if (query.isError) {
        return isNotFoundError(query.error) ? <TagNotFound /> : <ErrorState />;
    }

    return <TagDetailContent tag={query.data} />;
}
