import type { Story, StoryDefault } from '@ladle/react';

import { SearchSkeleton } from './SearchStates';

export default {
    title: 'Search',
} satisfies StoryDefault;

export const Loading: Story = () => <SearchSkeleton />;
Loading.meta = { width: 'large' };
