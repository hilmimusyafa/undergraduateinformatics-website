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
    title: 'Isi Masukan',
    subtitle: null,
    type: 'text',
    required: true,
    multiple: false,
    choices: [],
};

const dateQuestion: MsFormQuestion = {
    id: 'q2',
    title: 'Tanggal Kejadian',
    subtitle: null,
    type: 'date',
    required: false,
    multiple: false,
    choices: [],
};

const radioQuestion: MsFormQuestion = {
    id: 'q3',
    title: 'Jenis Masukan',
    subtitle: null,
    type: 'choice',
    required: true,
    multiple: false,
    choices: [
        { value: 'Saran', label: 'Saran', branchTargetId: null },
        { value: 'Keluhan', label: 'Keluhan', branchTargetId: null },
    ],
};

const checkboxQuestion: MsFormQuestion = {
    id: 'q4',
    title: 'Aspek yang Dinilai',
    subtitle: null,
    type: 'choice',
    required: false,
    multiple: true,
    choices: [
        { value: 'Akademik', label: 'Akademik', branchTargetId: null },
        { value: 'Administrasi', label: 'Administrasi', branchTargetId: null },
        { value: 'Fasilitas', label: 'Fasilitas', branchTargetId: null },
    ],
};

export default {
    title: 'Microsoft Form/Fields',
};

export const Text: Story = () => control({ questions: [textQuestion] });

export const DateField: Story = () => control({ questions: [dateQuestion] });

export const Radio: Story = () => control({ questions: [radioQuestion] });

export const Checkbox: Story = () => control({ questions: [checkboxQuestion] });
