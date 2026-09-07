import React, { useEffect, useState } from 'react';

import { createFileRoute } from '@tanstack/react-router';

import axios from 'axios';
import {
    AlertCircle,
    BookOpen,
    Calendar,
    CheckCircle,
    Clock,
    ExternalLink,
    Eye,
    FileText,
    Loader2,
    MapPin,
    Trash2,
    User,
    Users,
    X,
} from 'lucide-react';

export const Route = createFileRoute('/admin/reservation')({
    component: AdminReservation,
});

interface Reservation {
    id: number;
    date: string;
    shift: string;
    requested_by: string;
    document_link: string | null;
    meeting_room: string | null;
    study_program: string | null;
    participants: string | null;
    agenda: string | null;
    city: string | null;
    prodi_signature_name: string | null;
    prodi_signature_position: string | null;
    related_party_signature_name: string | null;
    related_party_signature_position: string | null;
    created_at: string;
}

const SHIFT_LABELS: Record<string, string> = {
    '09:00:00': '09:00 WIB',
    '13:00:00': '13:00 WIB',
    '15:00:00': '15:00 WIB',
};

function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}

function DetailModal({ reservation, onClose }: { reservation: Reservation; onClose: () => void }) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
            <div className="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                {/* Header Modal */}
                <div className="flex items-center justify-between border-b border-gray-100 p-6">
                    <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100">
                            <Calendar size={20} className="text-[#9F1521]" />
                        </div>
                        <div>
                            <h3 className="font-bold text-gray-800">Detail Reservasi</h3>
                            <p className="text-xs text-gray-500">ID #{reservation.id}</p>
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100"
                    >
                        <X size={18} />
                    </button>
                </div>

                {/* Content */}
                <div className="space-y-4 p-6">
                    {/* Info Utama */}
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <DetailItem
                            icon={<Calendar size={16} />}
                            label="Tanggal"
                            value={formatDate(reservation.date)}
                        />
                        <DetailItem
                            icon={<Clock size={16} />}
                            label="Sesi / Shift"
                            value={SHIFT_LABELS[reservation.shift] ?? reservation.shift}
                        />
                        <DetailItem
                            icon={<User size={16} />}
                            label="Diajukan Oleh"
                            value={reservation.requested_by}
                        />
                        <DetailItem
                            icon={<MapPin size={16} />}
                            label="Ruang Pertemuan"
                            value={reservation.meeting_room ?? '-'}
                        />
                        <DetailItem
                            icon={<BookOpen size={16} />}
                            label="Program Studi"
                            value={reservation.study_program ?? 'S1 Informatika'}
                        />
                        <DetailItem
                            icon={<Users size={16} />}
                            label="Peserta"
                            value={reservation.participants ?? '-'}
                        />
                    </div>

                    {/* Agenda */}
                    {reservation.agenda && (
                        <div className="rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <p className="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                Agenda
                            </p>
                            <p className="text-sm leading-relaxed text-gray-800">
                                {reservation.agenda}
                            </p>
                        </div>
                    )}

                    {/* Tanda Tangan */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="rounded-xl border border-red-100 bg-red-50 p-4">
                            <p className="mb-2 text-xs font-semibold tracking-wide text-red-700 uppercase">
                                Pihak Prodi
                            </p>
                            <p className="text-sm font-medium text-gray-800">
                                {reservation.prodi_signature_name ?? '-'}
                            </p>
                            <p className="text-xs text-gray-500">
                                {reservation.prodi_signature_position ?? '-'}
                            </p>
                        </div>
                        <div className="rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <p className="mb-2 text-xs font-semibold tracking-wide text-blue-700 uppercase">
                                Pihak Terkait
                            </p>
                            <p className="text-sm font-medium text-gray-800">
                                {reservation.related_party_signature_name ??
                                    reservation.requested_by}
                            </p>
                            <p className="text-xs text-gray-500">
                                {reservation.related_party_signature_position ?? '-'}
                            </p>
                        </div>
                    </div>

                    {/* PDF Link */}
                    {reservation.document_link ? (
                        <a
                            href={reservation.document_link}
                            target="_blank"
                            rel="noreferrer"
                            className="flex w-full items-center justify-center gap-2 rounded-xl bg-[#9F1521] py-3 font-medium text-white transition-colors hover:bg-red-800"
                        >
                            <FileText size={18} />
                            Lihat Berita Acara (PDF)
                            <ExternalLink size={14} />
                        </a>
                    ) : (
                        <div className="flex w-full items-center justify-center gap-2 rounded-xl bg-gray-100 py-3 font-medium text-gray-400">
                            <FileText size={18} />
                            Dokumen PDF belum tersedia
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

function DetailItem({
    icon,
    label,
    value,
}: {
    icon: React.ReactNode;
    label: string;
    value: string;
}) {
    return (
        <div className="flex items-start gap-3">
            <div className="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                {icon}
            </div>
            <div>
                <p className="text-xs font-medium text-gray-400">{label}</p>
                <p className="text-sm font-semibold text-gray-800">{value}</p>
            </div>
        </div>
    );
}

function AdminReservation() {
    const [reservations, setReservations] = useState<Reservation[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [selected, setSelected] = useState<Reservation | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);
    const [toast, setToast] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

    const showToast = (type: 'success' | 'error', message: string) => {
        setToast({ type, message });
        setTimeout(() => setToast(null), 3000);
    };

    const fetchReservations = async () => {
        try {
            setLoading(true);
            setError(null);
            const res = await axios.get('/api/reservation/schedule');
            setReservations(res.data.data ?? []);
        } catch {
            setError('Gagal memuat data reservasi dari server.');
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm('Yakin ingin menghapus reservasi ini?')) return;
        try {
            setDeletingId(id);
            await axios.delete(`/api/reservation/schedule/${id}`);
            setReservations((prev) => prev.filter((r) => r.id !== id));
            showToast('success', 'Reservasi berhasil dihapus.');
        } catch {
            showToast('error', 'Gagal menghapus reservasi.');
        } finally {
            setDeletingId(null);
        }
    };

    useEffect(() => {
        fetchReservations();
    }, []);

    return (
        <div className="space-y-6">
            {/* Toast Notif */}
            {toast && (
                <div
                    className={`fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3 font-medium text-white shadow-lg transition-all ${toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'}`}
                >
                    {toast.type === 'success' ? (
                        <CheckCircle size={18} />
                    ) : (
                        <AlertCircle size={18} />
                    )}
                    {toast.message}
                </div>
            )}

            {/* Modal Detail */}
            {selected && <DetailModal reservation={selected} onClose={() => setSelected(null)} />}

            {/* Header */}
            <div className="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 className="text-xl font-bold text-gray-800">Manajemen Reservasi</h2>
                    <p className="mt-0.5 text-sm text-gray-500">
                        Daftar pengajuan reservasi ruang pertemuan dengan Prodi
                    </p>
                </div>
                <span className="rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-[#9F1521]">
                    {reservations.length} Reservasi
                </span>
            </div>

            {/* State: Loading */}
            {loading && (
                <div className="flex flex-col items-center justify-center rounded-xl border border-gray-100 bg-white p-16 text-gray-400">
                    <Loader2 className="mb-4 animate-spin text-[#9F1521]" size={40} />
                    <p className="font-medium text-gray-500">Memuat data reservasi...</p>
                </div>
            )}

            {/* State: Error */}
            {!loading && error && (
                <div className="flex flex-col items-center justify-center rounded-xl border border-gray-100 bg-white p-16 text-red-500">
                    <AlertCircle size={48} className="mb-4 opacity-80" />
                    <p className="mb-4 font-medium">{error}</p>
                    <button
                        onClick={fetchReservations}
                        className="rounded-lg bg-red-100 px-4 py-2 font-medium text-[#9F1521] transition-colors hover:bg-red-200"
                    >
                        Coba Lagi
                    </button>
                </div>
            )}

            {/* State: Empty */}
            {!loading && !error && reservations.length === 0 && (
                <div className="flex flex-col items-center justify-center rounded-xl border border-gray-100 bg-white p-16 text-gray-400">
                    <Calendar size={48} className="mb-4 opacity-40" />
                    <p className="font-medium text-gray-500">Belum ada pengajuan reservasi</p>
                    <p className="mt-1 text-sm">
                        Pengajuan dari mahasiswa atau pihak terkait akan muncul di sini.
                    </p>
                </div>
            )}

            {/* Tabel Data */}
            {!loading && !error && reservations.length > 0 && (
                <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 bg-gray-50">
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Tanggal
                                    </th>
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Sesi
                                    </th>
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Diajukan Oleh
                                    </th>
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Ruangan
                                    </th>
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Berita Acara
                                    </th>
                                    <th className="px-6 py-4 text-left text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {reservations.map((r) => (
                                    <tr key={r.id} className="transition-colors hover:bg-gray-50">
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-gray-800">
                                                {new Date(r.date).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'short',
                                                    year: 'numeric',
                                                })}
                                            </div>
                                            <div className="text-xs text-gray-400">
                                                {new Date(r.date).toLocaleDateString('id-ID', {
                                                    weekday: 'long',
                                                })}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                <Clock size={11} />
                                                {SHIFT_LABELS[r.shift] ?? r.shift}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-gray-800">
                                                {r.requested_by}
                                            </div>
                                            {r.study_program && (
                                                <div className="text-xs text-gray-400">
                                                    {r.study_program}
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-gray-600">
                                            {r.meeting_room ?? (
                                                <span className="text-gray-300 italic">—</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4">
                                            {r.document_link ? (
                                                <a
                                                    href={r.document_link}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="inline-flex items-center gap-1 text-xs font-medium text-[#9F1521] underline underline-offset-2 hover:text-red-800"
                                                >
                                                    <FileText size={13} /> Lihat PDF
                                                </a>
                                            ) : (
                                                <span className="text-xs text-gray-300 italic">
                                                    Belum ada
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2">
                                                <button
                                                    onClick={() => setSelected(r)}
                                                    title="Lihat Detail"
                                                    className="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 transition-colors hover:bg-gray-200"
                                                >
                                                    <Eye size={15} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(r.id)}
                                                    disabled={deletingId === r.id}
                                                    title="Hapus Reservasi"
                                                    className="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-[#9F1521] transition-colors hover:bg-red-100 disabled:opacity-40"
                                                >
                                                    {deletingId === r.id ? (
                                                        <Loader2
                                                            size={14}
                                                            className="animate-spin"
                                                        />
                                                    ) : (
                                                        <Trash2 size={15} />
                                                    )}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
        </div>
    );
}
