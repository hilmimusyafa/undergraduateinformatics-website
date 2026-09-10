import { createFileRoute, notFound, useParams } from '@tanstack/react-router';

import axios from 'axios';

import { ErrorState } from '@/components/ErrorState';
import { PostPage } from '@/features/post/PostPage';
import { PostNotFound, PostSkeleton } from '@/features/post/PostStates';
import { type PostPayload } from '@/features/post/types';
import { isSuccessPayload, pageQueryKey } from '@/hooks/usePageData';
import { isNotFoundError } from '@/lib/errors';
import { seoHead, seoTitle } from '@/lib/seo';

export const Route = createFileRoute('/_site/posts/$slug')({
    loader: async ({ context, params }) => {
        const endpoint = `/api/posts/${params.slug}`;
        const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        if (isSuccessPayload<PostPayload['data']>(initialData)) {
            context.queryClient.setQueryData(pageQueryKey(endpoint), initialData);
            clearInitialData();
            return initialData;
        }

        if (initialData !== undefined && initialData !== null) {
            clearInitialData();
            throw notFound();
        }

        try {
            return await context.queryClient.ensureQueryData<PostPayload>({
                queryKey: pageQueryKey(endpoint),
                queryFn: () => axios.get<PostPayload>(endpoint).then((response) => response.data),
            });
        } catch (error) {
            if (isNotFoundError(error)) {
                throw notFound();
            }
            throw error;
        }
    },
    head: ({ loaderData }) => {
        const post = loaderData?.data;

        return seoHead('postDetail', {
            title: post ? seoTitle(post.title) : undefined,
            description: post?.subtitle || undefined,
        });
    },
    pendingComponent: PostSkeleton,
    pendingMs: 0,
    pendingMinMs: 0,
    errorComponent: PostErrorComponent,
    notFoundComponent: PostNotFound,
    component: PostRouteComponent,
});

function PostRouteComponent() {
    const { slug } = useParams({ from: '/_site/posts/$slug' });
    return <PostPage slug={slug} />;
}

function PostErrorComponent() {
    return <ErrorState />;
}

function clearInitialData() {
    (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__ = null;
}
