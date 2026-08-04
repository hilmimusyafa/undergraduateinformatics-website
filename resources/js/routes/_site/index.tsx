import { createFileRoute } from '@tanstack/react-router';

import { usePageData } from '../../hooks/usePageData';

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

    return (
        <div className="space-y-4">
            {data && (
                <>
                    {data.title && <title>{data.title}</title>}
                    {data.description && <meta name="description" content={data.description} />}
                </>
            )}
            <h1 className="text-primary text-4xl font-extrabold">Hello World</h1>
        </div>
    );
}
