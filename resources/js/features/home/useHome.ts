import { useSuspensePageData } from '@/hooks/usePageData';

import { type HomeData, type HomePayload } from './types';

export function useHome() {
    return useSuspensePageData<HomePayload, HomeData>('/api/home', {
        select: (response) => response.data,
    });
}
