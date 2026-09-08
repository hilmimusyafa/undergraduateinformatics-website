import type { Story } from '@ladle/react';

import { ErrorState } from '@/components/ErrorState';

import { PostSkeleton } from './PostStates';

export default {
    title: 'Posts/States',
};

export const DetailLoading: Story = () => <PostSkeleton />;
DetailLoading.meta = { width: 'large' };

export const DetailError: Story = () => <ErrorState />;
DetailError.meta = { width: 'large' };
