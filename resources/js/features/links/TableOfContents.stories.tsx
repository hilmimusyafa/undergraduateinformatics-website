import type { Story, StoryDefault } from '@ladle/react';

import { TableOfContents } from './TableOfContents';
import { type LinkSection } from './types';

const sectionsFixture: LinkSection[] = [
    { id: 1, name: 'Akademik', order_number: 1, links: [] },
    { id: 2, name: 'MBKM', order_number: 2, links: [] },
    { id: 6, name: 'Alumni', order_number: 3, links: [] },
];

export default {
    title: 'Links/TableOfContents',
} satisfies StoryDefault;

export const Default: Story = () => (
    <TableOfContents sections={sectionsFixture} onSelect={() => undefined} />
);
