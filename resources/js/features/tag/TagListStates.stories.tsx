import type { Story } from '@ladle/react';

import { PageError } from '@/components/PageError';

import { TagListSkeleton } from './TagListStates';

export default {
    title: 'Tags/States',
};

export const Loading: Story = () => <TagListSkeleton />;

export const Error: Story = () => <PageError />;
