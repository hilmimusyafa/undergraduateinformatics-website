import { createFileRoute } from '@tanstack/react-router';
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Loader2, AlertCircle, CheckCircle, MessageSquare, Eye } from 'lucide-react';

export const Route = createFileRoute('/admin/feedback')({
    component: AdminFeedback,
});

function AdminFeedback() {
    const [feedbackLink, setFeedbackLink] = useState('');
    const [feedbackStatus, setFeedbackStatus] = useState({ loading: false, type: '', message: '' });

    useEffect(() => {
        fetchFeedbackData();
    }, []);

    const fetchFeedbackData = async () => {
        try {
            setFeedbackStatus({ loading: true, type: '', message: '' });
            const response = await axios.get('/api/feedbackLink');
            if (response.data.success) {
                setFeedbackLink(response.data.feedback_link.link);
            }
        } catch (err) {
            console.error(err);
            setFeedbackStatus({ loading: false, type: 'error', message: 'Gagal mengambil data link feedback.' });
        } finally {
            setFeedbackStatus(prev => ({ ...prev, loading: false }));
        }
    };

    const handleSaveFeedback = async () => {
        try {
            setFeedbackStatus({ loading: true, type: '', message: 'Menyimpan...' });
            const response = await axios.post('/api/feedbackLink', { feedback_link: feedbackLink });
            if (response.data.success) {
                setFeedbackStatus({ loading: false, type: 'success', message: 'Link feedback berhasil diperbarui!' });
            }
        } catch (err: any) {
            console.error(err);
            const errorMessage = err.response?.data?.message || 'Gagal menyimpan link feedback. Pastikan URL valid.';
            setFeedbackStatus({ loading: false, type: 'error', message: errorMessage });
        }
    };

    return (
        <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 min-h-[500px]">
            <h2 className="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Manajemen Tautan Feedback</h2>
            
            <div className="mb-8 p-6 border border-gray-200 rounded-xl bg-gray-50">
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                        URL Tautan Feedback (Google Forms, dll)
                    </label>
                    <div className="flex gap-4">
                        <input 
                            type="url" 
                            value={feedbackLink}
                            onChange={(e) => setFeedbackLink(e.target.value)}
                            placeholder="https://forms.gle/..."
                            className="flex-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9F1521] focus:border-[#9F1521] outline-none transition-shadow"
                        />
                        <button 
                            onClick={handleSaveFeedback}
                            disabled={feedbackStatus.loading}
                            className={`flex items-center gap-2 px-6 py-2 rounded-lg font-medium transition-colors ${feedbackStatus.loading ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#9F1521] text-white hover:bg-red-800'}`}
                        >
                            {feedbackStatus.loading ? <Loader2 className="animate-spin" size={18} /> : <CheckCircle size={18} />}
                            Simpan
                        </button>
                    </div>
                </div>
                
                {feedbackStatus.message && (
                    <div className={`p-4 rounded-lg mt-4 flex items-center gap-3 ${feedbackStatus.type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`}>
                        {feedbackStatus.type === 'error' ? <AlertCircle size={20} /> : <CheckCircle size={20} />}
                        <span className="font-medium">{feedbackStatus.message}</span>
                    </div>
                )}
            </div>

            <div className="bg-blue-50 border border-blue-100 p-6 rounded-xl text-blue-800 flex gap-4 items-start">
                <MessageSquare className="text-blue-500 flex-shrink-0 mt-1" size={24} />
                <div>
                    <h3 className="font-bold mb-1">Informasi Fitur</h3>
                    <p className="text-sm mb-4">
                        Tautan yang Anda masukkan di atas akan dihubungkan ke halaman publik Feedback. Pastikan URL sudah benar dan dapat diakses oleh publik (umumnya menggunakan Google Forms).
                    </p>
                    {feedbackLink && (
                        <a href={feedbackLink} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 underline">
                            <Eye size={16} /> Test Tautan Saat Ini
                        </a>
                    )}
                </div>
            </div>
        </div>
    );
}
