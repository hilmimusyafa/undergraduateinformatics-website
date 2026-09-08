import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { useMsFormLogic } from '../hooks/useMsFormLogic';
import { type MsFormQuestion } from '../types/ms-forms';
import { MsFormView } from './MsFormView';

vi.mock('axios', async () => {
    const actual = await vi.importActual<typeof import('axios')>('axios');

    return {
        ...actual,
        default: {
            ...actual.default,
            post: vi.fn(),
        },
    };
});

const title = { text: 'Form Reservasi' };
const description = { text: 'Isi formulir di bawah ini.' };

const questions: MsFormQuestion[] = [
    {
        id: 'nama',
        title: { text: 'Nama Lengkap' },
        subtitle: null,
        type: 'text',
        required: true,
        multiple: false,
        choices: [],
    },
    {
        id: 'keperluan',
        title: { text: 'Keperluan' },
        subtitle: null,
        type: 'text',
        required: true,
        multiple: false,
        choices: [],
    },
];

function renderView(extension?: Parameters<typeof useMsFormLogic>[0]['extension']) {
    const queryClient = new QueryClient({
        defaultOptions: {
            mutations: { retry: false },
            queries: { retry: false },
        },
    });

    function Harness() {
        const logic = useMsFormLogic({
            questions,
            submitUrl: '/api/reservation',
            extension,
        });

        return <MsFormView logic={logic} title={title} description={description} />;
    }

    return render(
        <QueryClientProvider client={queryClient}>
            <Harness />
        </QueryClientProvider>
    );
}

describe('MsFormView', () => {
    beforeEach(() => {
        vi.mocked(axios.post).mockResolvedValue({ data: { success: true } });
    });

    it('renders the title, question headings, and submit button', () => {
        renderView();

        expect(screen.getByRole('heading', { name: 'Form Reservasi' })).toBeInTheDocument();
        expect(screen.getByText('Isi formulir di bawah ini.')).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Nama Lengkap' })).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Keperluan' })).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /Kirim/ })).toBeInTheDocument();
    });

    it('renders an extra field error beneath its question', async () => {
        const extension = {
            fieldExtraErrors: (values: Record<string, string | string[]>) => ({
                keperluan: values.keperluan ? 'Keperluan tidak valid.' : null,
            }),
        };

        renderView(extension);

        expect(screen.queryByText('Keperluan tidak valid.')).not.toBeInTheDocument();

        await userEvent.type(screen.getByRole('textbox', { name: 'Keperluan' }), 'Pertemuan');

        expect(await screen.findByText('Keperluan tidak valid.')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /Kirim/ })).toBeEnabled();
    });
});
