import React from 'react';

import { Link, Outlet, createFileRoute } from '@tanstack/react-router';

import { Calendar, FileText, LayoutDashboard, LogOut, MessageSquare, Upload } from 'lucide-react';

export const Route = createFileRoute('/admin')({
    component: AdminLayout,
});

function AdminLayout() {
    return (
        <div className="flex h-screen bg-gray-50">
            {/* Sidebar */}
            <div className="z-10 flex w-64 flex-col bg-[#9F1521] text-white shadow-xl">
                <a
                    href="/"
                    title="Kembali ke Homepage"
                    className="flex justify-center border-b border-red-800 p-6 transition-colors hover:bg-white/10"
                >
                    <img
                        src="/images/logo4.png"
                        alt="Logo Telkom"
                        className="h-10 w-auto object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.6)]"
                    />
                </a>
                <nav className="mt-2 flex-1 space-y-2 overflow-y-auto p-4">
                    <SidebarLink
                        to="/admin"
                        icon={<LayoutDashboard size={20} />}
                        label="Dashboard Statistik"
                        exact
                    />
                    <SidebarLink
                        to="/admin/informasi"
                        icon={<FileText size={20} />}
                        label="Manajemen Informasi"
                    />
                    <SidebarLink
                        to="/admin/upload"
                        icon={<Upload size={20} />}
                        label="Upload Data Excel"
                    />
                    <SidebarLink
                        to="/admin/feedback"
                        icon={<MessageSquare size={20} />}
                        label="Manajemen Feedback"
                    />
                    <SidebarLink
                        to="/admin/reservation"
                        icon={<Calendar size={20} />}
                        label="Approval Reservasi"
                    />
                </nav>
                <div className="space-y-1 border-t border-red-800 p-4">
                    <a
                        href="/admin/logout"
                        className="flex w-full items-center gap-3 rounded-lg p-3 text-red-100 transition-colors hover:bg-white/10 hover:text-white"
                    >
                        <LogOut size={20} />
                        <span className="font-medium">Logout</span>
                    </a>
                </div>
            </div>

            {/* Main Content */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Header */}
                <header className="z-0 flex items-center justify-between bg-white p-4 px-8 shadow-sm">
                    <h1 className="text-2xl font-semibold text-gray-800">Admin Panel</h1>
                    <div className="flex items-center gap-3">
                        <div className="text-right">
                            <p className="text-sm font-medium text-gray-700">Administrator</p>
                            <p className="text-xs text-gray-500">admin@bif.edu</p>
                        </div>
                        <div className="flex h-10 w-10 items-center justify-center rounded-full border border-red-200 bg-red-100 text-lg font-bold text-[#9F1521] shadow-sm">
                            A
                        </div>
                    </div>
                </header>

                {/* Content Area */}
                <main className="flex-1 overflow-y-auto p-8">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}

function SidebarLink({
    to,
    icon,
    label,
    exact = false,
}: {
    to: string;
    icon: React.ReactNode;
    label: string;
    exact?: boolean;
}) {
    return (
        <Link
            to={to}
            activeOptions={{ exact }}
            className="flex w-full items-center gap-3 rounded-lg p-3 transition-all duration-200 hover:bg-white/10"
            activeProps={{ className: 'bg-white/20 shadow-md font-semibold text-white' }}
            inactiveProps={{ className: 'font-medium text-white/80 hover:text-white' }}
        >
            {icon}
            <span>{label}</span>
        </Link>
    );
}
