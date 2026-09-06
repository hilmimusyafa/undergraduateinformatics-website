import type { Story, StoryDefault } from '@ladle/react';

import { PostCardSkeleton } from './PostCardStates';

export default {
    title: 'PostCard/States',
} satisfies StoryDefault;

export const Loading: Story = () => <PostCardSkeleton />;
