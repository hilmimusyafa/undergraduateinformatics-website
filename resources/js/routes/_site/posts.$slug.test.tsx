import { QueryClient } from '@tanstack/react-query';

import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { pageQueryKey } from '@/hooks/usePageData';
import { seoDefaults, seoPage } from '@/lib/seo';
import { axiosError } from '@/test/mocks';

import { Route } from './posts.$slug';

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

const loader = Route.options.loader as unknown as (args: {
    context: { queryClient: QueryClient };
    params: { slug: string };
}) => Promise<unknown>;

function createQueryClient() {
    return new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });
}

const payload = { status: 'success', data: { title: 'Registrasi Ganjil 2026' } };

beforeEach(() => {
    vi.mocked(axios.get).mockReset();
    delete (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;
});

describe('posts detail route', () => {
    it('uses the post title and subtitle from loader data for the head', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({
            loaderData: {
                data: { title: 'Registrasi Ganjil 2026', subtitle: 'Info registrasi' },
            },
        });
        expect(result.meta?.[0]?.title).toBe(`Registrasi Ganjil 2026 - ${seoDefaults.title}`);
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe('Info registrasi');
    });

    it('falls back to the static postDetail config without loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('postDetail').title);
        expect(result.meta?.find((entry) => entry.name === 'description')?.content).toBe(
            seoPage('postDetail').description
        );
    });

    it('keeps the post detail page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});

describe('posts detail route loader', () => {
    it('seeds the query cache from initial data and clears the global', async () => {
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = payload;
        const queryClient = createQueryClient();

        const result = await loader({ context: { queryClient }, params: { slug: 'a' } });

        expect(result).toEqual(payload);
        expect(queryClient.getQueryData(pageQueryKey('/api/posts/a'))).toEqual(payload);
        expect((window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__).toBeNull();
        expect(axios.get).not.toHaveBeenCalled();
    });

    it('throws notFound for the server not-found marker', async () => {
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = { notFound: true };
        const queryClient = createQueryClient();

        await expect(
            loader({ context: { queryClient }, params: { slug: 'missing' } })
        ).rejects.toThrow();
        expect((window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__).toBeNull();
    });

    it('fetches and caches on client navigation using the shared query key', async () => {
        const queryClient = createQueryClient();
        vi.mocked(axios.get).mockResolvedValue({ data: payload });

        const result = await loader({ context: { queryClient }, params: { slug: 'b' } });

        expect(result).toEqual(payload);
        expect(queryClient.getQueryData(pageQueryKey('/api/posts/b'))).toEqual(payload);
        expect(axios.get).toHaveBeenCalledWith('/api/posts/b');
    });

    it('throws notFound when the api returns 404', async () => {
        const queryClient = createQueryClient();
        vi.mocked(axios.get).mockRejectedValue(axiosError(404));

        await expect(loader({ context: { queryClient }, params: { slug: 'x' } })).rejects.toThrow();
    });

    it('registers not-found, error, and pending components', () => {
        expect(Route.options.notFoundComponent).toBeDefined();
        expect(Route.options.errorComponent).toBeDefined();
        expect(Route.options.pendingComponent).toBeDefined();
    });
});
