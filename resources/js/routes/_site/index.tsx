import { createFileRoute } from '@tanstack/react-router';

import { usePageData } from '../../hooks/usePageData';
import { seoPage } from '../../lib/seo';

export interface HomePagePayload {
    title?: string;
    description?: string;
    tags?: any[];
    posts?: any[];
    sections?: any[];
}

export const Route = createFileRoute('/_site/')({
    component: HomePage,
});

function HomePage() {
    const { data } = usePageData<HomePagePayload>('/api/home');
    const { title, description } = seoPage('home');

    return (
        <div className="space-y-4">
            {data && (
                <>
                    <title>{data.title ?? title}</title>
                    <meta name="description" content={data.description ?? description} />
                </>
            )}
            <h1 className="text-primary text-4xl font-extrabold">Hello World</h1>
        </div>
    );
}
