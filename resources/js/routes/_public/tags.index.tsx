import { createFileRoute } from '@tanstack/react-router';

import { ErrorState } from '@/components/ErrorState';
import { TagListPage } from '@/features/tag/TagListPage';
import { TagListSkeleton } from '@/features/tag/TagListStates';
import { ensurePageData } from '@/hooks/usePageData';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_public/tags/')({
    loader: ({ context }) => ensurePageData(context.queryClient, '/api/tags'),
    head: () => seoHead('tagList'),
    pendingComponent: TagListSkeleton,
    errorComponent: ErrorState,
    component: TagListPage,
});
