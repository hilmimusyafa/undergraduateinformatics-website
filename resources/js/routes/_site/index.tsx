import { createFileRoute } from '@tanstack/react-router';

import { ErrorState } from '@/components/ErrorState';
import { HomePage } from '@/features/home/HomePage';
import { HomeSkeleton } from '@/features/home/HomeStates';
import { ensurePageData } from '@/hooks/usePageData';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/')({
    loader: ({ context }) => ensurePageData(context.queryClient, '/api/home'),
    head: () => seoHead('home'),
    pendingComponent: HomeSkeleton,
    errorComponent: ErrorState,
    component: HomePage,
});
