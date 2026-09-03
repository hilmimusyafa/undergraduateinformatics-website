import { createFileRoute } from '@tanstack/react-router';

import { TagsPage } from '@/features/tags/TagsPage';

export const Route = createFileRoute('/_site/tags')({
    head: () => ({
        meta: [
            { title: 'Daftar Label - Portal Informasi Sarjana Informatika' },
            {
                name: 'description',
                content:
                    'Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan label.',
            },
        ],
    }),
    component: TagsPage,
});
