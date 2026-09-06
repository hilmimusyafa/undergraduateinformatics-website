import { PageError } from '@/components/PageError';

import { LinksContent } from './LinksContent';
import { LinksSkeleton } from './LinksStates';
import { useLinks } from './useLinks';

export function LinksPage() {
    const query = useLinks();

    if (query.isPending) {
        return <LinksSkeleton />;
    }

    if (query.isError) {
        return <PageError />;
    }

    const sections = query.data;

    return <LinksContent sections={sections} />;
}
