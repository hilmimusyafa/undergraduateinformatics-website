import { createFileRoute } from '@tanstack/react-router';
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
    Calendar, Loader2, AlertCircle, FileText,
    Trash2, Eye, X, ExternalLink, Clock, MapPin,
    User, Users, BookOpen, CheckCircle
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
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
}

function DetailModal({ reservation, onClose }: { reservation: Reservation; onClose: () => void }) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                {/* Header Modal */}
                <div className="flex items-center justify-between p-6 border-b border-gray-100">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <Calendar size={20} className="text-[#9F1521]" />
                        </div>
                        <div>
                            <h3 className="font-bold text-gray-800">Detail Reservasi</h3>
                            <p className="text-xs text-gray-500">ID #{reservation.id}</p>
                        </div>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500">
                        <X size={18} />
                    </button>
                </div>

                {/* Content */}
                <div className="p-6 space-y-4">
                    {/* Info Utama */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <DetailItem icon={<Calendar size={16} />} label="Tanggal" value={formatDate(reservation.date)} />
                        <DetailItem icon={<Clock size={16} />} label="Sesi / Shift" value={SHIFT_LABELS[reservation.shift] ?? reservation.shift} />
                        <DetailItem icon={<User size={16} />} label="Diajukan Oleh" value={reservation.requested_by} />
                        <DetailItem icon={<MapPin size={16} />} label="Ruang Pertemuan" value={reservation.meeting_room ?? '-'} />
                        <DetailItem icon={<BookOpen size={16} />} label="Program Studi" value={reservation.study_program ?? 'S1 Informatika'} />
                        <DetailItem icon={<Users size={16} />} label="Peserta" value={reservation.participants ?? '-'} />
                    </div>

                    {/* Agenda */}
                    {reservation.agenda && (
                        <div className="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p className="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Agenda</p>
                            <p className="text-sm text-gray-800 leading-relaxed">{reservation.agenda}</p>
                        </div>
                    )}

                    {/* Tanda Tangan */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="p-4 bg-red-50 rounded-xl border border-red-100">
                            <p className="text-xs font-semibold text-red-700 uppercase tracking-wide mb-2">Pihak Prodi</p>
                            <p className="text-sm font-medium text-gray-800">{reservation.prodi_signature_name ?? '-'}</p>
                            <p className="text-xs text-gray-500">{reservation.prodi_signature_position ?? '-'}</p>
                        </div>
                        <div className="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <p className="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-2">Pihak Terkait</p>
                            <p className="text-sm font-medium text-gray-800">{reservation.related_party_signature_name ?? reservation.requested_by}</p>
                            <p className="text-xs text-gray-500">{reservation.related_party_signature_position ?? '-'}</p>
                        </div>
                    </div>

                    {/* PDF Link */}
                    {reservation.document_link ? (
                        <a
                            href={reservation.document_link}
                            target="_blank"
                            rel="noreferrer"
                            className="flex items-center justify-center gap-2 w-full py-3 bg-[#9F1521] text-white rounded-xl font-medium hover:bg-red-800 transition-colors"
                        >
                            <FileText size={18} />
                            Lihat Berita Acara (PDF)
                            <ExternalLink size={14} />
                        </a>
                    ) : (
                        <div className="flex items-center gap-2 w-full py-3 bg-gray-100 text-gray-400 rounded-xl font-medium justify-center">
                            <FileText size={18} />
                            Dokumen PDF belum tersedia
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

function DetailItem({ icon, label, value }: { icon: React.ReactNode; label: string; value: string }) {
    return (
        <div className="flex items-start gap-3">
            <div className="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 text-gray-500">
                {icon}
            </div>
            <div>
                <p className="text-xs text-gray-400 font-medium">{label}</p>
                <p className="text-sm text-gray-800 font-semibold">{value}</p>
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
            setReservations(prev => prev.filter(r => r.id !== id));
            showToast('success', 'Reservasi berhasil dihapus.');
        } catch {
            showToast('error', 'Gagal menghapus reservasi.');
        } finally {
            setDeletingId(null);
        }
    };

    useEffect(() => { fetchReservations(); }, []);

    return (
        <div className="space-y-6">
            {/* Toast Notif */}
            {toast && (
                <div className={`fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg text-white font-medium transition-all ${toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'}`}>
                    {toast.type === 'success' ? <CheckCircle size={18} /> : <AlertCircle size={18} />}
                    {toast.message}
                </div>
            )}

            {/* Modal Detail */}
            {selected && <DetailModal reservation={selected} onClose={() => setSelected(null)} />}

            {/* Header */}
            <div className="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 className="text-xl font-bold text-gray-800">Manajemen Reservasi</h2>
                    <p className="text-sm text-gray-500 mt-0.5">Daftar pengajuan reservasi ruang pertemuan dengan Prodi</p>
                </div>
                <span className="bg-red-100 text-[#9F1521] text-sm font-semibold px-3 py-1 rounded-full">
                    {reservations.length} Reservasi
                </span>
            </div>

            {/* State: Loading */}
            {loading && (
                <div className="bg-white rounded-xl border border-gray-100 p-16 flex flex-col items-center justify-center text-gray-400">
                    <Loader2 className="animate-spin mb-4 text-[#9F1521]" size={40} />
                    <p className="font-medium text-gray-500">Memuat data reservasi...</p>
                </div>
            )}

            {/* State: Error */}
            {!loading && error && (
                <div className="bg-white rounded-xl border border-gray-100 p-16 flex flex-col items-center justify-center text-red-500">
                    <AlertCircle size={48} className="mb-4 opacity-80" />
                    <p className="font-medium mb-4">{error}</p>
                    <button onClick={fetchReservations} className="px-4 py-2 bg-red-100 text-[#9F1521] rounded-lg hover:bg-red-200 transition-colors font-medium">
                        Coba Lagi
                    </button>
                </div>
            )}

            {/* State: Empty */}
            {!loading && !error && reservations.length === 0 && (
                <div className="bg-white rounded-xl border border-gray-100 p-16 flex flex-col items-center justify-center text-gray-400">
                    <Calendar size={48} className="mb-4 opacity-40" />
                    <p className="font-medium text-gray-500">Belum ada pengajuan reservasi</p>
                    <p className="text-sm mt-1">Pengajuan dari mahasiswa atau pihak terkait akan muncul di sini.</p>
                </div>
            )}

            {/* Tabel Data */}
            {!loading && !error && reservations.length > 0 && (
                <div className="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100">
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Tanggal</th>
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Sesi</th>
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Diajukan Oleh</th>
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Ruangan</th>
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Berita Acara</th>
                                    <th className="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {reservations.map((r) => (
                                    <tr key={r.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-gray-800">{new Date(r.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                                            <div className="text-xs text-gray-400">{new Date(r.date).toLocaleDateString('id-ID', { weekday: 'long' })}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                <Clock size={11} />
                                                {SHIFT_LABELS[r.shift] ?? r.shift}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-gray-800">{r.requested_by}</div>
                                            {r.study_program && <div className="text-xs text-gray-400">{r.study_program}</div>}
                                        </td>
                                        <td className="px-6 py-4 text-gray-600">{r.meeting_room ?? <span className="text-gray-300 italic">—</span>}</td>
                                        <td className="px-6 py-4">
                                            {r.document_link ? (
                                                <a href={r.document_link} target="_blank" rel="noreferrer"
                                                    className="inline-flex items-center gap-1 text-[#9F1521] hover:text-red-800 font-medium text-xs underline underline-offset-2">
                                                    <FileText size={13} /> Lihat PDF
                                                </a>
                                            ) : (
                                                <span className="text-xs text-gray-300 italic">Belum ada</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2">
                                                <button
                                                    onClick={() => setSelected(r)}
                                                    title="Lihat Detail"
                                                    className="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"
                                                >
                                                    <Eye size={15} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(r.id)}
                                                    disabled={deletingId === r.id}
                                                    title="Hapus Reservasi"
                                                    className="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-[#9F1521] transition-colors disabled:opacity-40"
                                                >
                                                    {deletingId === r.id ? <Loader2 size={14} className="animate-spin" /> : <Trash2 size={15} />}
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
