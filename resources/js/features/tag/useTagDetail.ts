import { usePageData } from '@/hooks/usePageData';
import { type ApiSuccessResponse } from '@/types/api';

import { type TagWithPosts } from './types';

export function useTagDetail(slug: string) {
    return usePageData<ApiSuccessResponse<TagWithPosts>, TagWithPosts>(`/api/tags/${slug}`, {
        select: (response) => response.data,
    });
}
