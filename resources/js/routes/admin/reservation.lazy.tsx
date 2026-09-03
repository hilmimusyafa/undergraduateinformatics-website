import { createLazyFileRoute } from '@tanstack/react-router';

import { Calendar } from 'lucide-react';

export const Route = createLazyFileRoute('/admin/reservation')({
    component: Component,
});

export function Component() {
    return (
        <div className="flex min-h-[400px] flex-col items-center justify-center rounded-xl border border-gray-100 bg-white p-6 text-gray-400 shadow-sm">
            <Calendar size={48} className="mx-auto mb-4 opacity-50" />
            <p className="font-medium text-gray-500">Fitur Approval Reservasi</p>
            <p className="text-sm">Fitur ini akan segera tersedia di pembaruan selanjutnya.</p>
        </div>
    );
}
