import { useState } from 'react';

import { useMutation } from '@tanstack/react-query';

import axios from 'axios';

import { buildMsFormAnswers } from '../lib/ms-form-answers';
import { type MsFormQuestion, type MsFormSection, type MsFormValues } from '../types/ms-forms';

export function useMsFormSubmission(
    submitUrl: string,
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[]
) {
    const [submitError, setSubmitError] = useState<string | null>(null);

    const submitForm = useMutation({
        mutationFn: async (values: MsFormValues) => {
            await axios.post(submitUrl, {
                answers: buildMsFormAnswers(sections, questions, values),
            });
        },
        onError: (error) => {
            const status = axios.isAxiosError(error) ? error.response?.status : undefined;

            setSubmitError(
                status === 404
                    ? 'Formulir sedang tidak tersedia.'
                    : 'Gagal mengirim jawaban. Silakan coba beberapa saat lagi.'
            );
        },
    });

    const resetSubmitError = () => setSubmitError(null);

    return { submitForm, submitError, resetSubmitError };
}
