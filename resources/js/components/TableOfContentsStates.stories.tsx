import type { Story, StoryDefault } from '@ladle/react';

import { TableOfContentsSkeleton } from './TableOfContentsStates';

export default {
    title: 'TableOfContents/States',
} satisfies StoryDefault;

export const Loading: Story = () => <TableOfContentsSkeleton />;
