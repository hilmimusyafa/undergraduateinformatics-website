import { describe, expect, it } from 'vitest';

import { type MsFormQuestion, type MsFormSection, type MsFormValues } from '../types/ms-forms';
import { buildMsFormAnswers } from './ms-form-answers';

const sections: MsFormSection[] = [
    { id: 'section-1', title: null, subtitle: null, questionIds: ['q1'] },
    { id: 'section-2', title: null, subtitle: null, questionIds: ['q2'] },
    { id: 'section-3', title: null, subtitle: null, questionIds: ['q3'] },
];

const questions: MsFormQuestion[] = [
    {
        id: 'q1',
        title: 'Rahasiakan identitas?',
        subtitle: null,
        type: 'choice',
        required: true,
        multiple: false,
        choices: [
            { value: 'Ya', label: 'Ya', branchTargetId: 'q3' },
            { value: 'Tidak', label: 'Tidak', branchTargetId: 'q2' },
        ],
    },
    {
        id: 'q2',
        title: 'Nama Lengkap',
        subtitle: null,
        type: 'text',
        required: false,
        multiple: false,
        choices: [],
    },
    {
        id: 'q3',
        title: 'Jenis Masukan',
        subtitle: null,
        type: 'choice',
        required: true,
        multiple: true,
        choices: [
            { value: 'Saran', label: 'Saran', branchTargetId: null },
            { value: 'Keluhan', label: 'Keluhan', branchTargetId: null },
        ],
    },
];

describe('buildMsFormAnswers', () => {
    it('returns only reachable questions with non-empty answers', () => {
        const values: MsFormValues = { q1: 'Ya', q2: 'Budi', q3: ['Saran'] };

        expect(buildMsFormAnswers(sections, questions, values)).toEqual([
            { questionId: 'q1', answer: 'Ya' },
            { questionId: 'q3', answer: ['Saran'] },
        ]);
    });

    it('skips unreachable questions when a branch skips a section', () => {
        const values: MsFormValues = { q1: 'Ya', q2: '', q3: 'Keluhan' };

        expect(buildMsFormAnswers(sections, questions, values)).toEqual([
            { questionId: 'q1', answer: 'Ya' },
            { questionId: 'q3', answer: 'Keluhan' },
        ]);
    });

    it('drops empty optional answers', () => {
        const values: MsFormValues = { q1: 'Tidak', q2: '  ', q3: [] };

        expect(buildMsFormAnswers(sections, questions, values)).toEqual([
            { questionId: 'q1', answer: 'Tidak' },
        ]);
    });
});
