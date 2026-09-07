import { ErrorState } from '@/components/ErrorState';

import { LinksContent } from './LinksContent';
import { LinksSkeleton } from './LinksStates';
import { useLinks } from './useLinks';

export function LinksPage() {
    const query = useLinks();

    if (query.isPending) {
        return <LinksSkeleton />;
    }

    if (query.isError) {
        return <ErrorState />;
    }

    const sections = query.data;

    return <LinksContent sections={sections} />;
}
