import { MsForm } from '@/components/MsForm';
import { MsFormUnavailable } from '@/components/MsFormStates';
import { useMsForm } from '@/hooks/useMsForm';

import { ReservationForm } from './ReservationForm';

export function ReservationPage() {
    const { data } = useMsForm('/api/reservation');

    if (!data.isValid) {
        return <MsFormUnavailable />;
    }

    if (!data.reservation) {
        return (
            <MsForm
                title={data.title}
                description={data.description}
                sections={data.sections}
                questions={data.questions}
                submitUrl="/api/reservation"
            />
        );
    }

    return (
        <ReservationForm
            title={data.title}
            description={data.description}
            sections={data.sections}
            questions={data.questions}
            submitUrl="/api/reservation"
            reservation={data.reservation}
        />
    );
}
