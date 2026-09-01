import type { Story } from '@ladle/react';

import { NavItem } from './NavItem';
import { RouterHarness } from './RouterHarness';

export default {
    title: 'Navigation/NavItem',
};

export const Top: Story = () => (
    <RouterHarness>
        <div className="flex items-center gap-3">
            <NavItem variant="top" to="/">
                Beranda
            </NavItem>
            <NavItem variant="top" to="/explore">
                Informasi
            </NavItem>
            <NavItem variant="top" to="/feedback">
                Masukan
            </NavItem>
        </div>
    </RouterHarness>
);

export const Side: Story = () => (
    <RouterHarness>
        <div className="flex w-64 flex-col">
            <NavItem variant="side" to="/">
                Beranda
            </NavItem>
            <NavItem variant="side" to="/explore">
                Informasi
            </NavItem>
            <NavItem variant="side" to="/feedback">
                Masukan
            </NavItem>
        </div>
    </RouterHarness>
);
