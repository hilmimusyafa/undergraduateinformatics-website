import { PostContent } from './PostContent';
import { usePost } from './usePost';

export function PostPage({ slug }: { slug: string }) {
    const { data: post } = usePost(slug);

    return <PostContent post={post} />;
}
