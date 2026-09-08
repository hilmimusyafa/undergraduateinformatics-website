import { MsFormView } from '@/components/MsFormView';
import {
    type MsFormQuestion,
    type MsFormSection,
    type MsRichText,
    type ReservationMetadata,
} from '@/types/ms-forms';

import { useReservationForm } from './useReservationForm';

interface ReservationFormProps {
    questions: MsFormQuestion[];
    sections?: MsFormSection[];
    title: MsRichText;
    description: MsRichText | null;
    submitUrl: string;
    reservation: ReservationMetadata;
}

export function ReservationForm({
    questions,
    sections,
    title,
    description,
    submitUrl,
    reservation,
}: ReservationFormProps) {
    const logic = useReservationForm({ questions, sections, submitUrl, reservation });

    return <MsFormView logic={logic} title={title} description={description} />;
}
