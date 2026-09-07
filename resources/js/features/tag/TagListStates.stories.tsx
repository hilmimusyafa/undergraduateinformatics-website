import type { Story } from '@ladle/react';

import { ErrorState } from '@/components/ErrorState';

import { TagListSkeleton } from './TagListStates';

export default {
    title: 'Tags/States',
};

export const Loading: Story = () => <TagListSkeleton />;

export const Error: Story = () => <ErrorState />;
