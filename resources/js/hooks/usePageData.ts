import { useEffect, useState } from 'react';

import {
    type QueryClient,
    type UseQueryOptions,
    type UseQueryResult,
    type UseSuspenseQueryOptions,
    type UseSuspenseQueryResult,
    useQuery,
    useSuspenseQuery,
} from '@tanstack/react-query';

import axios, { AxiosError, type AxiosResponse } from 'axios';
import NProgress from 'nprogress';

import { type ApiSuccessResponse } from '@/types/api';

interface PageInitialData {
    data: unknown;
    endpoint: string | null;
    paramsKey: string | null;
}

export type QueryParams = Record<string, string | number | undefined>;

export function pageQueryKey(apiEndpoint: string, params?: QueryParams) {
    return [apiEndpoint, params];
}

export function isSuccessPayload<TData>(value: unknown): value is ApiSuccessResponse<TData> {
    return (
        typeof value === 'object' &&
        value !== null &&
        (value as { status?: unknown }).status === 'success'
    );
}

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
        queryKey: pageQueryKey(apiEndpoint, params),
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

export function useSuspensePageData<TQueryFnData = unknown, TData = TQueryFnData>(
    apiEndpoint: string,
    queryOptions: Omit<UseSuspenseQueryOptions<TQueryFnData, Error, TData>, 'queryKey'> = {},
    params?: QueryParams
): UseSuspenseQueryResult<TData, Error> {
    return useSuspenseQuery<TQueryFnData, Error, TData>({
        queryKey: pageQueryKey(apiEndpoint, params),
        queryFn: () =>
            axios.get(apiEndpoint, { params }).then((response) => response.data as TQueryFnData),
        ...queryOptions,
    });
}

export function ensurePageData<TData = unknown>(
    queryClient: QueryClient,
    apiEndpoint: string,
    params?: QueryParams
): Promise<TData> {
    const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

    if (initialData !== undefined && initialData !== null) {
        queryClient.setQueryData(pageQueryKey(apiEndpoint, params), initialData);
        (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = null;
        return Promise.resolve(initialData as TData);
    }

    return queryClient.ensureQueryData<TData>({
        queryKey: pageQueryKey(apiEndpoint, params),
        queryFn: () =>
            axios.get(apiEndpoint, { params }).then((response) => response.data as TData),
    });
}
