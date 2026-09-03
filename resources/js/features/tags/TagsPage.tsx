import { TagsList } from './TagsList';
import { TagsEmpty, TagsError, TagsSkeleton } from './TagsStates';
import { useTags } from './useTags';

export function TagsPage() {
    const query = useTags();

    if (query.isPending) {
        return <TagsSkeleton />;
    }

    if (query.isError) {
        return <TagsError />;
    }

    const tags = query.data;

    if (tags.length === 0) {
        return <TagsEmpty />;
    }

    return <TagsList tags={tags} />;
}
