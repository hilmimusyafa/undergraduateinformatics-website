import { useForm } from 'react-hook-form';

import type { Story } from '@ladle/react';

import { type MsFormQuestion, type MsFormValues } from '../types/ms-forms';
import { MsFormField } from './MsFormField';

const control = ({ questions }: { questions: MsFormQuestion[] }) => {
    const Component = () => {
        const { control } = useForm<MsFormValues>({
            defaultValues: Object.fromEntries(questions.map((question) => [question.id, ''])),
        });

        return <MsFormField question={questions[0]} control={control} />;
    };

    return <Component />;
};

const textQuestion: MsFormQuestion = {
    id: 'q1',
    title: { text: 'Isi Masukan' },
    subtitle: null,
    type: 'text',
    required: true,
    multiple: false,
    choices: [],
};

const dateQuestion: MsFormQuestion = {
    id: 'q2',
    title: { text: 'Tanggal Kejadian' },
    subtitle: null,
    type: 'date',
    required: false,
    multiple: false,
    choices: [],
};

const radioQuestion: MsFormQuestion = {
    id: 'q3',
    title: { text: 'Jenis Masukan' },
    subtitle: null,
    type: 'choice',
    required: true,
    multiple: false,
    choices: [
        { value: 'Saran', label: { text: 'Saran' }, branchTargetId: null },
        { value: 'Keluhan', label: { text: 'Keluhan' }, branchTargetId: null },
    ],
};

const checkboxQuestion: MsFormQuestion = {
    id: 'q4',
    title: { text: 'Aspek yang Dinilai' },
    subtitle: null,
    type: 'choice',
    required: false,
    multiple: true,
    choices: [
        { value: 'Akademik', label: { text: 'Akademik' }, branchTargetId: null },
        { value: 'Administrasi', label: { text: 'Administrasi' }, branchTargetId: null },
        { value: 'Fasilitas', label: { text: 'Fasilitas' }, branchTargetId: null },
    ],
};

const richRadioQuestion: MsFormQuestion = {
    id: 'q5',
    title: { text: 'Jenis Masukan' },
    subtitle: null,
    type: 'choice',
    required: true,
    multiple: false,
    choices: [
        { value: 'Saran', label: { text: 'Saran', html: '<b>Saran</b>' }, branchTargetId: null },
        {
            value: 'Keluhan',
            label: { text: 'Keluhan', html: '<u>Keluhan</u>' },
            branchTargetId: null,
        },
    ],
};

const richCheckboxQuestion: MsFormQuestion = {
    id: 'q6',
    title: { text: 'Aspek yang Dinilai' },
    subtitle: null,
    type: 'choice',
    required: false,
    multiple: true,
    choices: [
        {
            value: 'Akademik',
            label: { text: 'Akademik', html: '<b>Akademik</b>' },
            branchTargetId: null,
        },
        {
            value: 'Administrasi',
            label: { text: 'Administrasi', html: '<i>Administrasi</i>' },
            branchTargetId: null,
        },
        { value: 'Fasilitas', label: { text: 'Fasilitas' }, branchTargetId: null },
    ],
};

export default {
    title: 'Microsoft Form/Fields',
};

export const Text: Story = () => control({ questions: [textQuestion] });

export const DateField: Story = () => control({ questions: [dateQuestion] });

export const Radio: Story = () => control({ questions: [radioQuestion] });

export const Checkbox: Story = () => control({ questions: [checkboxQuestion] });

export const RichRadio: Story = () => control({ questions: [richRadioQuestion] });

export const RichCheckbox: Story = () => control({ questions: [richCheckboxQuestion] });
