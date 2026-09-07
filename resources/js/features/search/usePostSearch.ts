import { usePageData } from '@/hooks/usePageData';

import { type PostSearchPayload, type SearchResult } from './types';

export function usePostSearch(q: string, page: number) {
    return usePageData<PostSearchPayload, SearchResult>(
        '/api/posts/search',
        { select: (response) => ({ posts: response.data, meta: response.meta }) },
        { q: q || undefined, page }
    );
}
