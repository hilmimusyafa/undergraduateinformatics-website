import { useForm } from 'react-hook-form';

import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, expect, it } from 'vitest';

import { buildMsFormDefaultValues } from '../schemas/ms-forms';
import { type MsFormQuestion, type MsFormValues } from '../types/ms-forms';
import { MsFormField } from './MsFormField';

function renderField(question: MsFormQuestion) {
    const Control = ({ children }: { children: (control: any) => React.ReactNode }) => {
        const { control } = useForm<MsFormValues>({
            defaultValues: buildMsFormDefaultValues([question]),
            mode: 'onChange',
        });

        return <div>{children(control)}</div>;
    };

    return render(
        <Control>{(control) => <MsFormField question={question} control={control} />}</Control>
    );
}

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
        { value: 'Fasilitas', label: 'Fasilitas', branchTargetId: null },
    ],
};

describe('MsFormField', () => {
    it('renders a textarea for text questions', () => {
        renderField(textQuestion);

        expect(screen.getByRole('textbox', { name: 'Isi Masukan' })).toBeInTheDocument();
    });

    it('renders a date input for date questions', () => {
        renderField(dateQuestion);

        expect(screen.getByLabelText('Tanggal Kejadian')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('YYYY-MM-DD')).toBeInTheDocument();
    });

    it('renders radio inputs for single-choice questions', () => {
        renderField(radioQuestion);

        expect(screen.getByRole('radio', { name: 'Saran' })).toBeInTheDocument();
        expect(screen.getByRole('radio', { name: 'Keluhan' })).toBeInTheDocument();
    });

    it('renders checkboxes for multiple-choice questions', () => {
        renderField(checkboxQuestion);

        expect(screen.getByRole('checkbox', { name: 'Akademik' })).toBeInTheDocument();
        expect(screen.getByRole('checkbox', { name: 'Fasilitas' })).toBeInTheDocument();
    });

    it('toggles a checkbox selection on click', async () => {
        renderField(checkboxQuestion);

        const checkbox = screen.getByRole('checkbox', { name: 'Akademik' });
        await userEvent.click(checkbox);
        expect(checkbox).toBeChecked();

        await userEvent.click(checkbox);
        expect(checkbox).not.toBeChecked();
    });

    it('deselects a radio when clicking the selected option again', async () => {
        renderField(radioQuestion);

        const radio = screen.getByRole('radio', { name: 'Saran' });
        await userEvent.click(radio);
        expect(radio).toBeChecked();

        await userEvent.click(radio);
        expect(radio).not.toBeChecked();
    });
});
