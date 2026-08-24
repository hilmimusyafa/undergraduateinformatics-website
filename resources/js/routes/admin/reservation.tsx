import { createFileRoute } from '@tanstack/react-router';
import React from 'react';
import { Calendar } from 'lucide-react';

export const Route = createFileRoute('/admin/reservation')({
    component: AdminReservation,
});

function AdminReservation() {
    return (
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 min-h-[400px] flex flex-col items-center justify-center text-gray-400">
            <Calendar size={48} className="mx-auto mb-4 opacity-50" />
            <p className="font-medium text-gray-500">Fitur Approval Reservasi</p>
            <p className="text-sm">Fitur ini akan segera tersedia di pembaruan selanjutnya.</p>
        </div>
    );
}
