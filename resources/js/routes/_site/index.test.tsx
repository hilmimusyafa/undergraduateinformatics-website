import { type ReactNode } from 'react';

import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

import { render, screen, waitFor } from '@testing-library/react';
import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { HomePagePayload } from './index';
import { Route } from './index';

vi.mock('axios', async () => {
    const actual = await vi.importActual<typeof import('axios')>('axios');

    return {
        ...actual,
        default: {
            ...actual.default,
            get: vi.fn(),
        },
    };
});

function renderHome() {
    const Component = Route.options.component as () => ReactNode;

    return render(
        <QueryClientProvider
            client={
                new QueryClient({
                    defaultOptions: {
                        queries: { retry: false },
                    },
                })
            }
        >
            <Component />
        </QueryClientProvider>
    );
}

describe('HomePage route', () => {
    beforeEach(() => {
        delete (window as any).__INITIAL_DATA__;
    });

    it('renders the placeholder heading', async () => {
        vi.mocked(axios.get).mockResolvedValue({
            data: {} as HomePagePayload,
        });

        renderHome();

        expect(await screen.findByRole('heading', { name: 'Hello World' })).toBeInTheDocument();
    });

    it('sets the document title and description from the api payload', async () => {
        vi.mocked(axios.get).mockResolvedValue({
            data: {
                title: 'Beranda - Portal Informasi',
                description: 'Sumber informasi resmi.',
            } as HomePagePayload,
        });

        renderHome();

        await waitFor(() => {
            expect(document.title).toContain('Beranda - Portal Informasi');
            expect(
                document.querySelector('meta[name="description"]')?.getAttribute('content')
            ).toBe('Sumber informasi resmi.');
        });
    });

    it('falls back to the shared home meta when the payload omits it', async () => {
        vi.mocked(axios.get).mockResolvedValue({
            data: {} as HomePagePayload,
        });

        renderHome();

        await waitFor(() => {
            expect(document.title).toBe('Beranda - Portal Informasi Sarjana Informatika');
            expect(
                document.querySelector('meta[name="description"]')?.getAttribute('content')
            ).toBe(
                'Portal resmi Program Studi Sarjana Informatika Telkom University untuk informasi perkuliahan peserta didik.'
            );
        });
    });
});
