import { QueryClient } from '@tanstack/react-query';

import axios from 'axios';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { pageQueryKey } from '@/hooks/usePageData';
import { seoDefaults, seoPage } from '@/lib/seo';
import { axiosError } from '@/test/mocks';

import { Route } from './tags.$slug';

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

const payload = { status: 'success', data: { name: 'Beasiswa' } };

beforeEach(() => {
    vi.mocked(axios.get).mockReset();
    delete (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;
});

describe('tags detail route', () => {
    it('uses the tag name and description from loader data for the head title', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string }[];
        };

        const result = head({
            loaderData: { data: { name: 'Kurikulum', description: 'Info kurikulum' } },
        });
        expect(result.meta?.[0]?.title).toBe(`Kurikulum - ${seoDefaults.title}`);
    });

    it('sets the page description from loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({
            loaderData: { data: { name: 'Kurikulum', description: 'Info kurikulum' } },
        });
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe('Info kurikulum');
    });

    it('falls back to the static tagDetail config without loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('tagDetail').title);
        expect(result.meta?.find((entry) => entry.name === 'description')?.content).toBe(
            seoPage('tagDetail').description
        );
    });

    it('keeps the tag detail page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});

describe('tags detail route loader', () => {
    it('seeds the query cache from initial data and clears the global', async () => {
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = payload;
        const queryClient = createQueryClient();

        const result = await loader({ context: { queryClient }, params: { slug: 'beasiswa' } });

        expect(result).toEqual(payload);
        expect(queryClient.getQueryData(pageQueryKey('/api/tags/beasiswa'))).toEqual(payload);
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

        const result = await loader({ context: { queryClient }, params: { slug: 'kurikulum' } });

        expect(result).toEqual(payload);
        expect(queryClient.getQueryData(pageQueryKey('/api/tags/kurikulum'))).toEqual(payload);
        expect(axios.get).toHaveBeenCalledWith('/api/tags/kurikulum');
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
