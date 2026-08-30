import { Outlet, createFileRoute, Link } from '@tanstack/react-router';
import { LayoutDashboard, Upload, MessageSquare, Calendar, FileText, LogOut } from 'lucide-react';
import React from 'react';

export const Route = createFileRoute('/admin')({
    component: AdminLayout,
});

function AdminLayout() {
    return (
        <div className="flex h-screen bg-gray-50">
            {/* Sidebar */}
            <div className="w-64 bg-[#9F1521] text-white flex flex-col shadow-xl z-10">
                <a href="/" title="Kembali ke Homepage" className="p-6 border-b border-red-800 flex justify-center hover:bg-white/10 transition-colors">
                    <img src="/images/logo4.png" alt="Logo Telkom" className="h-10 w-auto object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.6)]" />
                </a>
                <nav className="flex-1 p-4 space-y-2 mt-2 overflow-y-auto">
                    <SidebarLink to="/admin" icon={<LayoutDashboard size={20} />} label="Dashboard Statistik" exact />
                    <SidebarLink to="/admin/informasi" icon={<FileText size={20} />} label="Manajemen Informasi" />
                    <SidebarLink to="/admin/upload" icon={<Upload size={20} />} label="Upload Data Excel" />
                    <SidebarLink to="/admin/feedback" icon={<MessageSquare size={20} />} label="Manajemen Feedback" />
                    <SidebarLink to="/admin/reservation" icon={<Calendar size={20} />} label="Approval Reservasi" />
                </nav>
                <div className="p-4 border-t border-red-800 space-y-1">
                    <a href="/admin/logout" className="w-full flex items-center gap-3 p-3 rounded-lg transition-colors hover:bg-white/10 text-red-100 hover:text-white">
                        <LogOut size={20} />
                        <span className="font-medium">Logout</span>
                    </a>
                </div>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                {/* Header */}
                <header className="bg-white shadow-sm p-4 px-8 flex justify-between items-center z-0">
                    <h1 className="text-2xl font-semibold text-gray-800">
                        Admin Panel
                    </h1>
                    <div className="flex items-center gap-3">
                        <div className="text-right">
                            <p className="text-sm font-medium text-gray-700">Administrator</p>
                            <p className="text-xs text-gray-500">admin@bif.edu</p>
                        </div>
                        <div className="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-[#9F1521] font-bold text-lg shadow-sm border border-red-200">
                            A
                        </div>
                    </div>
                </header>

                {/* Content Area */}
                <main className="flex-1 p-8 overflow-y-auto">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}

function SidebarLink({ to, icon, label, exact = false }: { to: string; icon: React.ReactNode; label: string; exact?: boolean }) {
    return (
        <Link 
            to={to}
            activeOptions={{ exact }}
            className="w-full flex items-center gap-3 p-3 rounded-lg transition-all duration-200 hover:bg-white/10"
            activeProps={{ className: 'bg-white/20 shadow-md font-semibold text-white' }}
            inactiveProps={{ className: 'font-medium text-white/80 hover:text-white' }}
        >
            {icon}
            <span>{label}</span>
        </Link>
    );
}
