import { PageError } from '@/components/PageError';

import { TagDetailContent } from './TagDetailContent';
import { TagDetailSkeleton } from './TagDetailStates';
import { useTagDetail } from './useTagDetail';

export function TagDetailPage({ slug }: { slug: string }) {
    const query = useTagDetail(slug);

    if (query.isPending) {
        return <TagDetailSkeleton />;
    }

    if (query.isError) {
        return <PageError />;
    }

    return <TagDetailContent tag={query.data} />;
}
