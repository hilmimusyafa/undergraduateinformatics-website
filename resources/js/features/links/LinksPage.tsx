import { LinksContent } from './LinksContent';
import { LinksEmpty, LinksError, LinksSkeleton } from './LinksStates';
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

    if (sections.length === 0) {
        return <LinksEmpty />;
    }

    return <LinksContent sections={sections} />;
}
