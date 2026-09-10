import { useSuspensePageData } from '@/hooks/usePageData';
import { type ApiSuccessResponse } from '@/types/api';

import { type TagWithCount } from './types';

export function useTagList() {
    return useSuspensePageData<ApiSuccessResponse<TagWithCount[]>, TagWithCount[]>('/api/tags', {
        select: (response) => response.data,
    });
}
