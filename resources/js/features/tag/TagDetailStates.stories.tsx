import type { Story } from '@ladle/react';

import { ErrorState } from '@/components/ErrorState';

import { TagDetailSkeleton } from './TagDetailStates';

export default {
    title: 'Tags/States',
};

export const DetailLoading: Story = () => <TagDetailSkeleton />;

export const DetailError: Story = () => <ErrorState />;
