import type { Story } from '@ladle/react';

import { NotFoundPage } from './NotFoundPage';
import { RouterHarness } from './RouterHarness';

export default {
    title: 'Status Pages/NotFoundPage',
};

export const Default: Story = () => (
    <RouterHarness>
        <NotFoundPage />
    </RouterHarness>
);
