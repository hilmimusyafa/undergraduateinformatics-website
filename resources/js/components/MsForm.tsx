import { useMsFormLogic } from '../hooks/useMsFormLogic';
import { type MsFormQuestion, type MsFormSection, type MsRichText } from '../types/ms-forms';
import { MsFormView } from './MsFormView';

interface MsFormProps {
    questions: MsFormQuestion[];
    sections?: MsFormSection[];
    title: MsRichText;
    description: MsRichText | null;
    submitUrl: string;
}

export function MsForm({ questions, sections, title, description, submitUrl }: MsFormProps) {
    const logic = useMsFormLogic({ questions, sections, submitUrl });

    return <MsFormView logic={logic} title={title} description={description} />;
}
