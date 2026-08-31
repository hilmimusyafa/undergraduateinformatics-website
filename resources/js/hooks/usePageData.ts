import { useEffect, useState } from 'react';

import { type UseQueryOptions, type UseQueryResult, useQuery } from '@tanstack/react-query';

import axios from 'axios';
import NProgress from 'nprogress';

interface PageInitialData {
    data: unknown;
    endpoint: string | null;
}

export function usePageData<TQueryFnData = unknown, TData = TQueryFnData>(
    apiEndpoint: string,
    queryOptions: Omit<UseQueryOptions<TQueryFnData, Error, TData>, 'queryKey'> = {}
): UseQueryResult<TData, Error> {
    const [initialState] = useState<PageInitialData>(() => {
        const globalInitialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        if (globalInitialData === undefined || globalInitialData === null) {
            return { data: null, endpoint: null };
        }

        return { data: globalInitialData, endpoint: apiEndpoint };
    });

    useEffect(() => {
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = null;
    }, []);

    const hasValidInitialData = initialState.data !== null && initialState.endpoint === apiEndpoint;

    return useQuery<TQueryFnData, Error, TData>({
        queryKey: [apiEndpoint],
        queryFn: async () => {
            NProgress.start();

            try {
                const response = await axios.get(apiEndpoint);
                return response.data as TQueryFnData;
            } finally {
                NProgress.done();
            }
        },
        initialData: hasValidInitialData ? (initialState.data as TQueryFnData) : undefined,
        staleTime: 30000,
        ...queryOptions,
        retry: queryOptions.retry ?? 1,
    });
}
