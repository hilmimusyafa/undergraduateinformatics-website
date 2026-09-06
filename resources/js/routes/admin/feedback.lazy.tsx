import { useEffect, useState } from 'react';

import { createLazyFileRoute } from '@tanstack/react-router';

import axios from 'axios';
import { AlertCircle, CheckCircle, Eye, Loader2, MessageSquare } from 'lucide-react';

export const Route = createLazyFileRoute('/admin/feedback')({
    component: Component,
});

export function Component() {
    const [feedbackLink, setFeedbackLink] = useState('');
    const [feedbackStatus, setFeedbackStatus] = useState({ loading: false, type: '', message: '' });

    useEffect(() => {
        const fetchFeedbackData = async () => {
            try {
                const response = await axios.get('/api/feedbackLink');
                if (response.data.success) {
                    setFeedbackLink(response.data.feedback_link.link);
                }
            } catch (err) {
                console.error(err);
                setFeedbackStatus({
                    loading: false,
                    type: 'error',
                    message: 'Gagal mengambil data link feedback.',
                });
            }
        };

        fetchFeedbackData();
    }, []);

    const handleSaveFeedback = async () => {
        try {
            setFeedbackStatus({ loading: true, type: '', message: 'Menyimpan...' });
            const response = await axios.post('/api/feedbackLink', { feedback_link: feedbackLink });
            if (response.data.success) {
                setFeedbackStatus({
                    loading: false,
                    type: 'success',
                    message: 'Link feedback berhasil diperbarui!',
                });
            }
        } catch (err: any) {
            console.error(err);
            const errorMessage =
                err.response?.data?.message || 'Gagal menyimpan link feedback. Pastikan URL valid.';
            setFeedbackStatus({ loading: false, type: 'error', message: errorMessage });
        }
    };

    return (
        <div className="min-h-[500px] rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 className="mb-6 border-b pb-2 text-xl font-bold text-gray-800">
                Manajemen Tautan Feedback
            </h2>

            <div className="mb-8 rounded-xl border border-gray-200 bg-gray-50 p-6">
                <div className="mb-4">
                    <label className="mb-2 block text-sm font-medium text-gray-700">
                        URL Tautan Feedback (Google Forms, dll)
                    </label>
                    <div className="flex gap-4">
                        <input
                            type="url"
                            value={feedbackLink}
                            onChange={(e) => setFeedbackLink(e.target.value)}
                            placeholder="https://forms.gle/..."
                            className="flex-1 rounded-lg border border-gray-300 p-3 transition-shadow outline-none focus:border-[#9F1521] focus:ring-2 focus:ring-[#9F1521]"
                        />
                        <button
                            onClick={handleSaveFeedback}
                            disabled={feedbackStatus.loading}
                            className={`flex items-center gap-2 rounded-lg px-6 py-2 font-medium transition-colors ${feedbackStatus.loading ? 'cursor-not-allowed bg-gray-200 text-gray-400' : 'bg-[#9F1521] text-white hover:bg-red-800'}`}
                        >
                            {feedbackStatus.loading ? (
                                <Loader2 className="animate-spin" size={18} />
                            ) : (
                                <CheckCircle size={18} />
                            )}
                            Simpan
                        </button>
                    </div>
                </div>

                {feedbackStatus.message && (
                    <div
                        className={`mt-4 flex items-center gap-3 rounded-lg p-4 ${feedbackStatus.type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`}
                    >
                        {feedbackStatus.type === 'error' ? (
                            <AlertCircle size={20} />
                        ) : (
                            <CheckCircle size={20} />
                        )}
                        <span className="font-medium">{feedbackStatus.message}</span>
                    </div>
                )}
            </div>

            <div className="flex items-start gap-4 rounded-xl border border-blue-100 bg-blue-50 p-6 text-blue-800">
                <MessageSquare className="mt-1 flex-shrink-0 text-blue-500" size={24} />
                <div>
                    <h3 className="mb-1 font-bold">Informasi Fitur</h3>
                    <p className="mb-4 text-sm">
                        Tautan yang Anda masukkan di atas akan dihubungkan ke halaman publik
                        Feedback. Pastikan URL sudah benar dan dapat diakses oleh publik (umumnya
                        menggunakan Google Forms).
                    </p>
                    {feedbackLink && (
                        <a
                            href={feedbackLink}
                            target="_blank"
                            rel="noreferrer"
                            className="inline-flex items-center gap-2 text-sm font-medium text-blue-600 underline hover:text-blue-800"
                        >
                            <Eye size={16} /> Test Tautan Saat Ini
                        </a>
                    )}
                </div>
            </div>
        </div>
    );
}
