import { createFileRoute } from '@tanstack/react-router';

import { TagListPage } from '@/features/tag/TagListPage';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/tags')({
    head: () => seoHead('tagList'),
    component: TagListPage,
});
