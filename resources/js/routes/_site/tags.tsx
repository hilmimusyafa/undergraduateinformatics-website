import { createFileRoute } from '@tanstack/react-router';

import { TagsPage } from '@/features/tags/TagsPage';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/tags')({
    head: () => seoHead('tagList'),
    component: TagsPage,
});
