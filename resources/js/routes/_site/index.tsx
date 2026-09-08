import { createFileRoute } from '@tanstack/react-router';

import { HomePage } from '@/features/home/HomePage';
import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/')({
    head: () => seoHead('home'),
    component: HomePage,
});
