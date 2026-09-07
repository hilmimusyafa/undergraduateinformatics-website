import { useEffect, useState } from 'react';

import { type UseQueryOptions, type UseQueryResult, useQuery } from '@tanstack/react-query';

import axios, { AxiosError, type AxiosResponse } from 'axios';
import NProgress from 'nprogress';

interface PageInitialData {
    data: unknown;
    endpoint: string | null;
    paramsKey: string | null;
}

type QueryParams = Record<string, string | number | undefined>;

function serializeParams(params?: QueryParams): string {
    if (!params) return '';

    return Object.entries(params)
        .filter(([, value]) => value !== undefined)
        .sort(([a], [b]) => a.localeCompare(b))
        .map(([key, value]) => `${key}=${String(value)}`)
        .join('&');
}

const NOT_FOUND_MARKER = Symbol('notFound');

function isNotFoundMarker(data: unknown): boolean {
    return (
        typeof data === 'object' &&
        data !== null &&
        'notFound' in data &&
        (data as { notFound?: unknown }).notFound === true
    );
}

function createNotFoundError(): AxiosError {
    const config = {} as AxiosResponse['config'];

    return new AxiosError(
        'Request failed with status code 404',
        AxiosError.ERR_BAD_REQUEST,
        config,
        undefined,
        { status: 404, statusText: 'Not Found', headers: {}, config, data: null }
    );
}

export function usePageData<TQueryFnData = unknown, TData = TQueryFnData>(
    apiEndpoint: string,
    queryOptions: Omit<UseQueryOptions<TQueryFnData, Error, TData>, 'queryKey'> = {},
    params?: QueryParams
): UseQueryResult<TData, Error> {
    const [initialState] = useState<PageInitialData>(() => {
        const globalInitialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        if (globalInitialData === undefined || globalInitialData === null) {
            return { data: null, endpoint: null, paramsKey: null };
        }

if (isNotFoundMarker(globalInitialData)) {
            return { data: NOT_FOUND_MARKER, endpoint: null, paramsKey: null };
        }

        return {
            data: globalInitialData,
            endpoint: apiEndpoint,
            paramsKey: serializeParams(params),
        };
    });

    useEffect(() => {
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = null;
    }, []);

    const paramsKey = serializeParams(params);
    const notFoundFromServer = initialState.data === NOT_FOUND_MARKER;
    const hasValidInitialData =
        !notFoundFromServer &&
        initialState.data !== null &&
        initialState.endpoint === apiEndpoint &&
        initialState.paramsKey === paramsKey;

    return useQuery<TQueryFnData, Error, TData>({
        queryKey: [apiEndpoint, params],
        queryFn: async () => {
            if (notFoundFromServer) {
                throw createNotFoundError();
            }

            NProgress.start();

            try {
                const response = await axios.get(apiEndpoint, { params });
                return response.data as TQueryFnData;
            } finally {
                NProgress.done();
            }
        },
        initialData: hasValidInitialData ? (initialState.data as TQueryFnData) : undefined,
        staleTime: 30000,
        ...queryOptions,
        retry: notFoundFromServer ? false : (queryOptions.retry ?? 1),
    });
}
