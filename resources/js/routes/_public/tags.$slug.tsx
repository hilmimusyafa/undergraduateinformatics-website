import { createFileRoute, notFound, useParams } from '@tanstack/react-router';

import axios from 'axios';

import { ErrorState } from '@/components/ErrorState';
import { TagDetailPage } from '@/features/tag/TagDetailPage';
import { TagDetailSkeleton, TagNotFound } from '@/features/tag/TagDetailStates';
import { type TagWithPostsPayload } from '@/features/tag/types';
import { isSuccessPayload, pageQueryKey } from '@/hooks/usePageData';
import { isNotFoundError } from '@/lib/errors';
import { seoHead, seoTitle } from '@/lib/seo';

export const Route = createFileRoute('/_public/tags/$slug')({
    loader: async ({ context, params }) => {
        const endpoint = `/api/tags/${params.slug}`;
        const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        if (isSuccessPayload<TagWithPostsPayload['data']>(initialData)) {
            context.queryClient.setQueryData(pageQueryKey(endpoint), initialData);
            clearInitialData();
            return initialData;
        }

        if (initialData !== undefined && initialData !== null) {
            clearInitialData();
            throw notFound();
        }

        try {
            return await context.queryClient.ensureQueryData<TagWithPostsPayload>({
                queryKey: pageQueryKey(endpoint),
                queryFn: () =>
                    axios.get<TagWithPostsPayload>(endpoint).then((response) => response.data),
            });
        } catch (error) {
            if (isNotFoundError(error)) {
                throw notFound();
            }
            throw error;
        }
    },
    head: ({ loaderData }) => {
        const tag = loaderData?.data;

        return seoHead('tagDetail', {
            title: tag ? seoTitle(tag.name) : undefined,
            description: tag?.description || undefined,
        });
    },
    pendingComponent: TagDetailSkeleton,
    pendingMs: 0,
    pendingMinMs: 0,
    errorComponent: TagErrorComponent,
    notFoundComponent: TagNotFound,
    component: TagDetailRouteComponent,
});

function TagDetailRouteComponent() {
    const { slug } = useParams({ from: '/_public/tags/$slug' });
    return <TagDetailPage slug={slug} />;
}

function TagErrorComponent() {
    return <ErrorState />;
}

function clearInitialData() {
    (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = null;
}
