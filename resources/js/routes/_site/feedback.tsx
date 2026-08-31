import { createFileRoute } from '@tanstack/react-router';

import { FeedbackPage } from '@/features/feedback/FeedbackPage';

export const Route = createFileRoute('/_site/feedback')({
    head: () => ({
        meta: [{ title: 'Masukan - Portal Informasi Sarjana Informatika' }],
    }),
    component: FeedbackPage,
});
