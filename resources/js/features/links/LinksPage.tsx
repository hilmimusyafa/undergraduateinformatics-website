import { LinksContent } from './LinksContent';
import { useLinks } from './useLinks';

export function LinksPage() {
    const { data: sections } = useLinks();

    return <LinksContent sections={sections} />;
}
