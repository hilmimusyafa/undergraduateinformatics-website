import { TagDetailContent } from './TagDetailContent';
import { useTagDetail } from './useTagDetail';

export function TagDetailPage({ slug }: { slug: string }) {
    const { data: tag } = useTagDetail(slug);

    return <TagDetailContent tag={tag} />;
}
