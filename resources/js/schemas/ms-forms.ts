import { z } from 'zod';

import { type MsFormAnswer, type MsFormQuestion, type MsFormValues } from '../types/ms-forms';

const MAX_TEXT_LENGTH = 4000;
const REQUIRED_MESSAGE = 'Pertanyaan ini wajib diisi';
const MAX_TEXT_LENGTH_MESSAGE = 'Jawaban maksimal 4000 karakter';
const INVALID_DATE_MESSAGE = 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.';

export function buildMsFormSchema(questions: MsFormQuestion[]) {
    const shape: Record<string, z.ZodType<MsFormAnswer, MsFormAnswer>> = {};

    for (const question of questions) {
        if (question.type === 'text') {
            let field = z.string().trim().max(MAX_TEXT_LENGTH, MAX_TEXT_LENGTH_MESSAGE);
            if (question.required) {
                field = field.min(1, REQUIRED_MESSAGE);
            }
            shape[question.id] = field;
        } else if (question.type === 'date') {
            let field = z
                .string()
                .trim()
                .pipe(z.union([z.iso.date(INVALID_DATE_MESSAGE), z.literal('')]));
            if (question.required) {
                field = field.refine((value) => value !== '', REQUIRED_MESSAGE);
            }
            shape[question.id] = field;
        } else if (question.multiple) {
            let field = z.array(z.string());
            if (question.required) {
                field = field.min(1, REQUIRED_MESSAGE);
            }
            shape[question.id] = field;
        } else {
            let field = z.string();
            if (question.required) {
                field = field.min(1, REQUIRED_MESSAGE);
            }
            shape[question.id] = field;
        }
    }

    return z.object(shape);
}

export function buildMsFormDefaultValues(questions: MsFormQuestion[]): MsFormValues {
    const defaults: MsFormValues = {};

    for (const question of questions) {
        defaults[question.id] = question.multiple ? [] : '';
    }

    return defaults;
}
