import { createFileRoute } from '@tanstack/react-router';

import { MsForm } from '@/components/MsForm';
import { MsFormError, MsFormSkeleton, MsFormUnavailable } from '@/components/MsFormStates';

import { useMsForm } from '../../hooks/useMsForm';

export const Route = createFileRoute('/_site/feedback')({
    head: () => ({
        meta: [{ title: 'Masukan - Portal Informasi Sarjana Informatika' }],
    }),
    component: FeedbackPage,
});

export function FeedbackPage() {
    const query = useMsForm('/api/feedback');

    if (query.isPending) {
        return <MsFormSkeleton />;
    }

    if (query.isError) {
        return <MsFormError />;
    }

    if (!query.data.isValid) {
        return <MsFormUnavailable />;
    }

    return (
        <MsForm
            title={query.data.title}
            description={query.data.description}
            sections={query.data.sections}
            questions={query.data.questions}
            submitUrl="/api/feedback"
        />
    );
}
