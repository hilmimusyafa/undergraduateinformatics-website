import { LinksContent } from './LinksContent';
import { LinksError, LinksSkeleton } from './LinksStates';
import { useLinks } from './useLinks';

export function LinksPage() {
    const query = useLinks();

    if (query.isPending) {
        return <LinksSkeleton />;
    }

    if (query.isError) {
        return <LinksError />;
    }

    const sections = query.data;

    return <LinksContent sections={sections} />;
}
