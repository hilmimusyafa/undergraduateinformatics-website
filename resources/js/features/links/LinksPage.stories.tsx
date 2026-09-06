import type { Story, StoryDefault } from '@ladle/react';

import { RouterHarness } from '@/components/RouterHarness';

import { LinksContent } from './LinksContent';
import { type LinkSection } from './types';

const sectionsFixture: LinkSection[] = [
    {
        id: 1,
        name: 'Akademik',
        order_number: 1,
        links: [
            {
                id: 3,
                name: 'SIAKAD',
                link: 'https://siakad.telkomuniversity.ac.id',
                updated_at: '2026-09-04T10:00:00.000000Z',
            },
            {
                id: 4,
                name: 'E-Campus',
                link: 'https://ecampus.telkomuniversity.ac.id',
                updated_at: '2026-09-04T09:00:00.000000Z',
            },
        ],
    },
    {
        id: 2,
        name: 'MBKM',
        order_number: 2,
        links: [
            {
                id: 5,
                name: 'Portal MBKM',
                link: 'https://mbkm.kemdikbud.go.id',
                updated_at: '2026-09-03T10:00:00.000000Z',
            },
        ],
    },
    {
        id: 6,
        name: 'Alumni',
        order_number: 3,
        links: [],
    },
];

export default {
    title: 'Links',
} satisfies StoryDefault;

export const Desktop: Story = () => (
    <RouterHarness>
        <LinksContent sections={sectionsFixture} />
    </RouterHarness>
);
Desktop.meta = { width: 'large' };

export const MobileTablet: Story = () => (
    <RouterHarness>
        <LinksContent sections={sectionsFixture} />
    </RouterHarness>
);
MobileTablet.meta = { width: 'medium' };
