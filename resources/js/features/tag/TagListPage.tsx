import { ErrorState } from '@/components/ErrorState';

import { TagListContent } from './TagListContent';
import { TagListSkeleton } from './TagListStates';
import { useTagList } from './useTagList';

export function TagListPage() {
    const query = useTagList();

    if (query.isPending) {
        return <TagListSkeleton />;
    }

    if (query.isError) {
        return <ErrorState />;
    }

    return <TagListContent tags={query.data} />;
}
