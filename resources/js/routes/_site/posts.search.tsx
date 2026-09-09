import { createFileRoute } from '@tanstack/react-router';

import { SearchPage } from '@/features/search/SearchPage';
import { type PostSearchPayload } from '@/features/search/types';
import { seoHead } from '@/lib/seo';

const searchValidator = {
    parse(input: Record<string, unknown>): { q?: string; page?: number } {
        const q = typeof input.q === 'string' ? input.q : undefined;

        let page: number | undefined;
        const raw = input.page;
        if (raw !== undefined && raw !== null && raw !== '') {
            const n = typeof raw === 'number' ? raw : Number(raw);
            page = Number.isInteger(n) && n >= 1 ? n : 1;
        }

        return { q, page };
    },
};

export const Route = createFileRoute('/_site/posts/search')({
    validateSearch: searchValidator,
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
