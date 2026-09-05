import { createFileRoute } from '@tanstack/react-router';

import { LinksPage } from '@/features/links/LinksPage';

export const Route = createFileRoute('/_site/links')({
    head: () => ({
        meta: [
            { title: 'Tautan Penting - Portal Informasi Sarjana Informatika' },
            {
                name: 'description',
                content:
                    'Kumpulan tautan penting terkait informasi di Program Studi Sarjana Informatika Telkom University.',
            },
        ],
    }),
    component: LinksPage,
});
