import { useEffect, useState } from 'react';

import { Outlet, createFileRoute } from '@tanstack/react-router';

import { SiteSideBar } from '../components/SiteSideBar';
import { TopBar } from '../components/TopBar';

export const Route = createFileRoute('/_site')({
    component: SiteLayout,
});

function SiteLayout() {
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    useEffect(() => {
        document.body.style.overflow = isSidebarOpen ? 'hidden' : '';

        return () => {
            document.body.style.overflow = '';
        };
    }, [isSidebarOpen]);

    return (
        <div className="relative min-h-screen">
            <TopBar
                isSidebarOpen={isSidebarOpen}
                onToggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)}
            />
            <SiteSideBar isOpen={isSidebarOpen} onClose={() => setIsSidebarOpen(false)} />

            <div
                className={`fixed inset-0 z-40 bg-black/40 transition-all duration-300 ${
                    isSidebarOpen ? 'visible opacity-100' : 'invisible opacity-0'
                }`}
                onClick={() => setIsSidebarOpen(false)}
            />

            <main className="content-grid">
                <Outlet />
            </main>
        </div>
    );
}
