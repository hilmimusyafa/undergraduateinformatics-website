import { MsForm } from '@/components/MsForm';
import { MsFormError, MsFormUnavailable } from '@/components/MsFormStates';
import { useMsForm } from '@/hooks/useMsForm';

import { ReservationForm } from './ReservationForm';
import { ReservationSkeleton } from './ReservationStates';

export function ReservationPage() {
    const query = useMsForm('/api/reservation');

    if (query.isPending) {
        return <ReservationSkeleton />;
    }

    if (query.isError) {
        return <MsFormError />;
    }

    if (!query.data.isValid) {
        return <MsFormUnavailable />;
    }

    if (!query.data.reservation) {
        return (
            <MsForm
                title={query.data.title}
                description={query.data.description}
                sections={query.data.sections}
                questions={query.data.questions}
                submitUrl="/api/reservation"
            />
        );
    }

    return (
        <ReservationForm
            title={query.data.title}
            description={query.data.description}
            sections={query.data.sections}
            questions={query.data.questions}
            submitUrl="/api/reservation"
            reservation={query.data.reservation}
        />
    );
}
