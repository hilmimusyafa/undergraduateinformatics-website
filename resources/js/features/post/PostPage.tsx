import { ErrorState } from '@/components/ErrorState';
import { isNotFoundError } from '@/lib/errors';

import { PostContent } from './PostContent';
import { PostNotFound, PostSkeleton } from './PostStates';
import { usePost } from './usePost';

export function PostPage({ slug }: { slug: string }) {
    const query = usePost(slug);

    if (query.isPending) {
        return <PostSkeleton />;
    }

    if (query.isError) {
        return isNotFoundError(query.error) ? <PostNotFound /> : <ErrorState />;
    }

    return <PostContent post={query.data} />;
}
