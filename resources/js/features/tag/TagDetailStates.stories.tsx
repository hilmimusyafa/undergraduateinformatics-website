import type { Story } from '@ladle/react';

import { PageError } from '@/components/PageError';

import { TagDetailSkeleton } from './TagDetailStates';

export default {
    title: 'Tags/States',
};

export const DetailLoading: Story = () => <TagDetailSkeleton />;

export const DetailError: Story = () => <PageError />;
