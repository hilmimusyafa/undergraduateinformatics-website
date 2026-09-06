import { createFileRoute, useParams } from '@tanstack/react-router';

import { TagDetailPage } from '@/features/tag/TagDetailPage';
import { type TagWithPostsPayload } from '@/features/tag/types';
import { seoDefaults, seoHead } from '@/lib/seo';

export const Route = createFileRoute('/_site/tags/$slug')({
    loader: () => {
        const initialData = (window as { __INITIAL_DATA__?: unknown }).__INITIAL_DATA__;

        return initialData !== undefined && initialData !== null
            ? (initialData as TagWithPostsPayload)
            : null;
    },
    head: ({ loaderData }) => {
        const tag = loaderData?.data;

        return seoHead('tagDetail', {
            title: tag ? `${tag.name} - ${seoDefaults.title}` : undefined,
            description: tag?.description ?? undefined,
        });
    },
    component: TagDetailRouteComponent,
});

function TagDetailRouteComponent() {
    const { slug } = useParams({ from: '/_site/tags/$slug' });
    return <TagDetailPage slug={slug} />;
}
