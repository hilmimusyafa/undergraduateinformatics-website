import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import axios, { AxiosError, type AxiosResponse } from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { MsForm } from './MsForm';
import {
    SUBMIT_URL,
    branchingPayload,
    richPayload,
    simplePayload,
    toFormProps,
} from './ms-form-fixtures';

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

function axiosError(status: number) {
    const error = new AxiosError('Request failed');
    error.response = { status, data: {} } as AxiosResponse;
    return error;
}

function renderForm(props: Parameters<typeof MsForm>[0]) {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });

    return render(
        <QueryClientProvider client={queryClient}>
            <MsForm {...props} />
        </QueryClientProvider>
    );
}

describe('MsForm', () => {
    beforeEach(() => {
        vi.mocked(axios.post).mockResolvedValue({ data: { success: true } });
    });

    it('renders title, description, and the first section questions', () => {
        renderForm(toFormProps(richPayload));

        expect(screen.getByRole('heading', { name: 'Form Umpan Balik' })).toBeInTheDocument();
        expect(
            screen.getByText(
                'Bantu kami meningkatkan layanan dengan mengisi formulir di bawah ini.'
            )
        ).toBeInTheDocument();
        expect(screen.getByRole('heading', { name: 'Jenis Masukan' })).toBeInTheDocument();
    });

    it('renders section title and subtitle', () => {
        renderForm(toFormProps(branchingPayload));

        expect(
            screen.getByRole('heading', { level: 2, name: 'Jenis Masukan' })
        ).toBeInTheDocument();
        expect(
            screen.getByText('Jawaban Anda menentukan bagian yang akan diisi.')
        ).toBeInTheDocument();
    });

    it('marks required questions with an asterisk', () => {
        renderForm(toFormProps(simplePayload));

        expect(screen.getByText('*')).toBeInTheDocument();
    });

    it('advances to the next section and offers a back button', async () => {
        renderForm(toFormProps(branchingPayload));

        await userEvent.click(screen.getByRole('radio', { name: 'Saran' }));
        await userEvent.click(screen.getByRole('button', { name: /Lanjut/ }));

        expect(await screen.findByRole('heading', { name: 'Detail Saran' })).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /Kembali/ })).toBeInTheDocument();
    });

    it('returns to the previous section when going back', async () => {
        renderForm(toFormProps(branchingPayload));

        await userEvent.click(screen.getByRole('radio', { name: 'Saran' }));
        await userEvent.click(screen.getByRole('button', { name: /Lanjut/ }));

        await screen.findByRole('heading', { level: 2, name: 'Detail Saran' });
        await userEvent.click(screen.getByRole('button', { name: /Kembali/ }));

        expect(
            screen.getByRole('heading', { level: 2, name: 'Jenis Masukan' })
        ).toBeInTheDocument();
        expect(screen.queryByRole('button', { name: /Kembali/ })).not.toBeInTheDocument();
    });

    it('requires answers to visible questions before advancing', async () => {
        renderForm(toFormProps(branchingPayload));

        await userEvent.click(screen.getByRole('button', { name: /Lanjut/ }));

        expect(await screen.findAllByText(/wajib diisi/)).toHaveLength(1);
        expect(axios.post).not.toHaveBeenCalled();
    });

    it('shows the submit button on the final section', async () => {
        renderForm(toFormProps(branchingPayload));

        await userEvent.click(screen.getByRole('radio', { name: 'Saran' }));
        await userEvent.click(screen.getByRole('button', { name: /Lanjut/ }));

        await screen.findByRole('heading', { level: 2, name: 'Detail Saran' });
        await userEvent.type(screen.getByRole('textbox', { name: 'Tuliskan Saran Anda' }), 'Saran');
        await userEvent.click(screen.getByRole('radio', { name: 'Besar' }));

        expect(await screen.findByRole('button', { name: /Kirim/ })).toBeInTheDocument();
        expect(screen.queryByRole('button', { name: /Lanjut/ })).not.toBeInTheDocument();
    });

    it('submits answers and shows the success state', async () => {
        renderForm(toFormProps(simplePayload));

        await userEvent.type(screen.getByRole('textbox'), 'Masukan saya');
        await userEvent.click(screen.getByRole('button', { name: /Kirim/ }));

        await waitFor(() => {
            expect(axios.post).toHaveBeenCalledWith(SUBMIT_URL, {
                answers: [{ questionId: 'isi', answer: 'Masukan saya' }],
            });
        });

        expect(await screen.findByText('Terima kasih!')).toBeInTheDocument();
    });

    it('resets the form after filling it again', async () => {
        renderForm(toFormProps(simplePayload));

        await userEvent.type(screen.getByRole('textbox'), 'Masukan saya');
        await userEvent.click(screen.getByRole('button', { name: /Kirim/ }));

        await screen.findByText('Terima kasih!');
        await userEvent.click(screen.getByRole('button', { name: /Isi Formulir Lagi/ }));

        expect(await screen.findByRole('textbox')).toHaveValue('');
        expect(screen.getByRole('button', { name: /Kirim/ })).toBeInTheDocument();
    });

    it('shows an error message when the submit request fails', async () => {
        vi.mocked(axios.post).mockRejectedValue(axiosError(422));
        renderForm(toFormProps(simplePayload));

        await userEvent.type(screen.getByRole('textbox'), 'Masukan saya');
        await userEvent.click(screen.getByRole('button', { name: /Kirim/ }));

        expect(
            await screen.findByText('Gagal mengirim jawaban. Silakan coba beberapa saat lagi.')
        ).toBeInTheDocument();
    });

    it('shows an unavailable message when the submit returns 404', async () => {
        vi.mocked(axios.post).mockRejectedValue(axiosError(404));
        renderForm(toFormProps(simplePayload));

        await userEvent.type(screen.getByRole('textbox'), 'Masukan saya');
        await userEvent.click(screen.getByRole('button', { name: /Kirim/ }));

        expect(await screen.findByText('Formulir sedang tidak tersedia.')).toBeInTheDocument();
    });
});
