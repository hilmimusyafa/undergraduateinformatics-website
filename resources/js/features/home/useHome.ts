import { usePageData } from '@/hooks/usePageData';

import { type HomeData, type HomePayload } from './types';

export function useHome() {
    return usePageData<HomePayload, HomeData>('/api/home', {
        select: (response) => response.data,
    });
}
