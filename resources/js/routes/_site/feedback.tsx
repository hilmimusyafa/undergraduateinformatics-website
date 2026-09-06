import { createFileRoute } from '@tanstack/react-router';

import { seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/feedback')({
    head: () => seoHead('feedback'),
});
