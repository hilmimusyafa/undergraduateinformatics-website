import { createFileRoute } from '@tanstack/react-router';

import { z } from 'zod';

import { SearchPage } from '@/features/search/SearchPage';
import { type PostSearchPayload } from '@/features/search/types';
import { seoHead } from '@/lib/seo';

const searchSchema = z.object({
    q: z.string().optional(),
    page: z.coerce.number().int().min(1).catch(1).optional(),
});

export const Route = createFileRoute('/_site/posts/search')({
    validateSearch: searchSchema,
    loader: () => {
        const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        return initialData !== undefined && initialData !== null
            ? (initialData as PostSearchPayload)
            : null;
    },
    head: () => seoHead('postSearch'),
    component: SearchRouteComponent,
});

function SearchRouteComponent() {
    const { q, page } = Route.useSearch();

    return <SearchPage q={q ?? ''} page={page ?? 1} />;
}
