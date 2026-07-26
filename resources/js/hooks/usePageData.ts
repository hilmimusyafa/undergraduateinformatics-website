import { useEffect, useState } from 'react';

import { type UseQueryResult, useQuery } from '@tanstack/react-query';

import axios from 'axios';
import NProgress from 'nprogress';

export function usePageData<T = any>(apiEndpoint: string): UseQueryResult<T, Error> {
    const [initialState] = useState<{ data: T | null; endpoint: string | null }>(() => {
        const globalInitialData = (window as any).__INITIAL_DATA__;
        if (globalInitialData !== undefined && globalInitialData !== null) {
            (window as any).__INITIAL_DATA__ = null;
            return {
                data: globalInitialData as T,
                endpoint: apiEndpoint,
            };
        }
        return {
            data: null,
            endpoint: null,
        };
    });

    const hasValidInitialData =
        initialState.data !== null &&
        initialState.data !== undefined &&
        initialState.endpoint === apiEndpoint;

    const query = useQuery<T, Error>({
        queryKey: [apiEndpoint],
        queryFn: async () => {
            NProgress.start();
            try {
                const response = await axios.get(apiEndpoint);
                return response.data as T;
            } finally {
                NProgress.done();
            }
        },
        initialData: hasValidInitialData ? (initialState.data as T) : undefined,
        staleTime: 30000,
    });

    useEffect(() => {
        if (query.isFetching && !hasValidInitialData) {
            NProgress.start();
        } else if (!query.isFetching) {
            NProgress.done();
        }
    }, [query.isFetching, hasValidInitialData]);

    return query;
}
