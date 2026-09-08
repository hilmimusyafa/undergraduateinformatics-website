import type { Story } from '@ladle/react';

import { FeedbackSkeleton } from './FeedbackStates';

export default {
    title: 'Feedback/States',
};

export const Loading: Story = () => <FeedbackSkeleton />;
