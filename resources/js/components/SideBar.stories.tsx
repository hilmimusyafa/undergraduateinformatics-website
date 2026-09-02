import type { Story } from '@ladle/react';

import { RouterHarness } from './RouterHarness';
import { SideBar } from './SideBar';

export default {
    title: 'Navigation/SideBar',
};

export const Open: Story = () => (
    <RouterHarness>
        <SideBar isOpen onClose={() => undefined} />
    </RouterHarness>
);
Open.meta = { width: 'medium' };

export const Closed: Story = () => (
    <RouterHarness>
        <div className="h-screen bg-gray-100 p-6">
            <p className="text-muted-foreground">Sidebar tersembunyi di luar layar (off-canvas).</p>
            <SideBar isOpen={false} onClose={() => undefined} />
        </div>
    </RouterHarness>
);
Closed.meta = { width: 'medium' };
