import { useCallback, useState } from 'react';

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
    const [fieldErrors, setFieldErrors] = useState<Record<string, string[]> | null>(null);

    const submitForm = useMutation({
        mutationFn: async (values: MsFormValues) => {
            await axios.post(submitUrl, {
                answers: buildMsFormAnswers(sections, questions, values),
            });
        },
        onError: (error) => {
            const status = axios.isAxiosError(error) ? error.response?.status : undefined;
            const data = axios.isAxiosError(error)
                ? (error.response?.data as Record<string, unknown> | undefined)
                : undefined;
            const errors = data?.errors as Record<string, string[]> | undefined;

            setFieldErrors(errors && typeof errors === 'object' ? errors : null);

            setSubmitError(
                status === 404
                    ? 'Formulir sedang tidak tersedia.'
                    : errors
                      ? null
                      : 'Gagal mengirim jawaban. Silakan coba beberapa saat lagi.'
            );
        },
    });

    const resetSubmitError = useCallback(() => {
        setSubmitError(null);
        setFieldErrors(null);
    }, []);

    return { submitForm, submitError, fieldErrors, resetSubmitError };
}
