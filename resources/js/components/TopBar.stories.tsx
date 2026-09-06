import type { Story } from '@ladle/react';

import { RouterHarness } from './RouterHarness';
import { TopBar } from './TopBar';

export default {
    title: 'Navigation/TopBar',
};

export const Desktop: Story = () => (
    <RouterHarness>
        <TopBar isSidebarOpen={false} onToggleSidebar={() => undefined} />
    </RouterHarness>
);
Desktop.meta = { width: 'large' };

export const Mobile: Story = () => (
    <RouterHarness>
        <TopBar isSidebarOpen={false} onToggleSidebar={() => undefined} />
    </RouterHarness>
);
Mobile.meta = { width: 'medium' };
