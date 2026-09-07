import { usePageData } from '@/hooks/usePageData';
import { type Post } from '@/types/post';

import { type PostPayload } from './types';

export function usePost(slug: string) {
    return usePageData<PostPayload, Post>(`/api/posts/${slug}`, {
        select: (response) => response.data,
    });
}
