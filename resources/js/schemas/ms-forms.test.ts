import { describe, expect, it } from 'vitest';

import { type MsFormQuestion } from '../types/ms-forms';
import { buildMsFormSchema } from './ms-forms';

const questions: MsFormQuestion[] = [
    {
        id: 'r1',
        title: 'Nama Lengkap',
        subtitle: null,
        type: 'text',
        required: true,
        multiple: false,
        choices: [],
    },
    {
        id: 'r2',
        title: 'Jenis Masukan',
        subtitle: 'Pilih salah satu',
        type: 'choice',
        required: true,
        multiple: false,
        choices: [
            { value: 'Saran', label: 'Saran', branchTargetId: null },
            { value: 'Keluhan', label: 'Keluhan', branchTargetId: null },
        ],
    },
    {
        id: 'r3',
        title: 'Tanggal Kejadian',
        subtitle: null,
        type: 'date',
        required: false,
        multiple: false,
        choices: [],
    },
];

describe('buildMsFormSchema', () => {
    it('rejects text answers longer than 4000 characters', () => {
        const schema = buildMsFormSchema(questions);

        expect(schema.safeParse({ r1: 'a'.repeat(4001), r2: 'Saran', r3: '' }).success).toBe(false);
        expect(schema.safeParse({ r1: 'a'.repeat(4000), r2: 'Saran', r3: '' }).success).toBe(true);
    });

    it('rejects a non-empty date answer with an invalid format', () => {
        const schema = buildMsFormSchema(questions);

        expect(schema.safeParse({ r1: 'Budi', r2: 'Saran', r3: '13-02-2026' }).success).toBe(false);
        expect(schema.safeParse({ r1: 'Budi', r2: 'Saran', r3: '2026-02-31' }).success).toBe(false);
        expect(schema.safeParse({ r1: 'Budi', r2: 'Saran', r3: '2026-02-28' }).success).toBe(true);
        expect(schema.safeParse({ r1: 'Budi', r2: 'Saran', r3: '' }).success).toBe(true);
    });

    it('requires at least one choice for a required multiple-choice question', () => {
        const multipleQuestions = [
            {
                id: 'm1',
                title: 'Topik',
                subtitle: null,
                type: 'choice' as const,
                required: true,
                multiple: true,
                choices: [
                    { value: 'A', label: 'A', branchTargetId: null },
                    { value: 'B', label: 'B', branchTargetId: null },
                ],
            },
        ];

        const schema = buildMsFormSchema(multipleQuestions);

        expect(schema.safeParse({ m1: [] }).success).toBe(false);
        expect(schema.safeParse({ m1: ['A'] }).success).toBe(true);
    });
});
