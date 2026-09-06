import { createFileRoute } from '@tanstack/react-router';

import { LinksPage } from '@/features/links/LinksPage';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/links')({
    head: () => seoHead('links'),
    component: LinksPage,
});
