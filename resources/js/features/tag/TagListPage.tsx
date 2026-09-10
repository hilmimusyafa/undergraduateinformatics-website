import { TagListContent } from './TagListContent';
import { useTagList } from './useTagList';

export function TagListPage() {
    const { data } = useTagList();

    return <TagListContent tags={data} />;
}
