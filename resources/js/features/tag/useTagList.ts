import { usePageData } from '@/hooks/usePageData';
import { type ApiSuccessResponse } from '@/types/api';

import { type TagWithCount } from './types';

export function useTagList() {
    return usePageData<ApiSuccessResponse<TagWithCount[]>, TagWithCount[]>('/api/tags', {
        select: (response) => response.data,
    });
}
