import { Outlet, createFileRoute } from '@tanstack/react-router';

import { TopBar } from '../components/TopBar';

export const Route = createFileRoute('/_site')({
    component: SiteLayout,
});

function SiteLayout() {
    return (
        <div className="bg-background text-foreground flex min-h-screen flex-col font-sans">
            <TopBar />
            <main className="flex w-full flex-col">
                <Outlet />
            </main>
        </div>
    );
}
