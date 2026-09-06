import { createFileRoute } from '@tanstack/react-router';

export const Route = createFileRoute('/_site/posts/$slug')({
    component: PostDetailPlaceholder,
});

function PostDetailPlaceholder() {
    return null;
}
