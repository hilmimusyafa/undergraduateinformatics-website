import { usePageData } from '@/hooks/usePageData';
import { type ApiSuccessResponse } from '@/types/api';

import { type LinkSection } from './types';

export function useLinks() {
    return usePageData<ApiSuccessResponse<LinkSection[]>, LinkSection[]>('/api/links', {
        select: (response) => response.data,
    });
}
