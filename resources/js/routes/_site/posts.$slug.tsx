import { createFileRoute, useParams } from '@tanstack/react-router';

import { PostPage } from '@/features/post/PostPage';
import { type PostPayload } from '@/features/post/types';
import { seoDefaults, seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/posts/$slug')({
    loader: () => {
        const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        return initialData !== undefined && initialData !== null
            ? (initialData as PostPayload)
            : null;
    },
    head: ({ loaderData }) => {
        const post = loaderData?.data;

        return seoHead('postDetail', {
            title: post ? `${post.title} - ${seoDefaults.title}` : undefined,
            description: post?.subtitle ?? undefined,
        });
    },
    component: PostRouteComponent,
});

function PostRouteComponent() {
    const { slug } = useParams({ from: '/_site/posts/$slug' });
    return <PostPage slug={slug} />;
}
