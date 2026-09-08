import type { Story, StoryDefault } from '@ladle/react';

import { LinkCardSkeleton } from './LinkCardStates';

export default {
    title: 'LinkCard/States',
} satisfies StoryDefault;

export const Loading: Story = () => <LinkCardSkeleton />;
