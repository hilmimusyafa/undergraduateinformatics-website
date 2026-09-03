import type { Story, StoryDefault } from '@ladle/react';

import { TagsContent } from './TagsContent';
import { type Tag } from './types';

const tagsFixture: Tag[] = [
    {
        id: 1,
        slug: 's1-informatika',
        name: 'S1 Informatika',
        description: 'Informasi resmi Program Studi Sarjana Informatika Telkom University',
        posts_count: 4,
    },
    {
        id: 2,
        slug: 'beasiswa',
        name: 'Beasiswa',
        description: 'Informasi beasiswa dalam dan luar negeri untuk mahasiswa',
        posts_count: 2,
    },
    {
        id: 3,
        slug: 'akademik',
        name: 'Akademik',
        description: 'Pengumuman akademik dan jadwal perkuliahan',
        posts_count: 3,
    },
    { id: 4, slug: 'mbkm', name: 'MBKM', description: null, posts_count: 0 },
];

export default {
    title: 'Tags',
} satisfies StoryDefault;

export const List: Story = () => <TagsContent tags={tagsFixture} />;
