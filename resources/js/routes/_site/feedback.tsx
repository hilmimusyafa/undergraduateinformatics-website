import { createFileRoute } from '@tanstack/react-router';

export const Route = createFileRoute('/_site/feedback')({
    head: () => ({
        meta: [
            { title: 'Masukan - Portal Informasi Sarjana Informatika' },
            {
                name: 'description',
                content:
                    'Berikan masukan dan evaluasi layanan untuk Program Studi Sarjana Informatika Telkom University melalui formulir.',
            },
        ],
    }),
});
