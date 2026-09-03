import type { Story } from '@ladle/react';

import { TagsEmpty, TagsError, TagsSkeleton } from './TagsStates';

export default {
    title: 'Tags/States',
};

export const Loading: Story = () => <TagsSkeleton />;

export const Empty: Story = () => <TagsEmpty />;

export const Error: Story = () => <TagsError />;
