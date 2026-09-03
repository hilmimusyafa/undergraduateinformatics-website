import { usePageData } from '@/hooks/usePageData';
import { type ApiSuccessResponse } from '@/types/api';

import { type Tag } from './types';

export function useTags() {
    return usePageData<ApiSuccessResponse<Tag[]>, Tag[]>('/api/tags', {
        select: (response) => response.data,
    });
}
