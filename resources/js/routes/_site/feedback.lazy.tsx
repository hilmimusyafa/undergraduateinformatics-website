import { createLazyFileRoute } from '@tanstack/react-router';

import { FeedbackPage } from '@/features/feedback/FeedbackPage';

export const Route = createLazyFileRoute('/_site/feedback')({
    component: FeedbackPage,
});
