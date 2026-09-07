import React, { useEffect, useState } from 'react';

import axios from 'axios';
import {
    AlertCircle,
    Calendar,
    CheckCircle,
    Eye,
    FileText,
    LayoutDashboard,
    Loader2,
    MessageSquare,
    Trash2,
    Upload,
    Users,
} from 'lucide-react';
import {
    Bar,
    BarChart,
    CartesianGrid,
    Cell,
    Legend,
    Line,
    LineChart,
    Pie,
    PieChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from 'recharts';

export default function AdminDashboard() {
    const [activeTab, setActiveTab] = useState('dashboard');
    const [datasets, setDatasets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Upload State
    const [selectedFile, setSelectedFile] = useState(null);
    const [previewData, setPreviewData] = useState(null);
    const [uploadStatus, setUploadStatus] = useState({ loading: false, type: '', message: '' });

    // Feedback State
    const [feedbackLink, setFeedbackLink] = useState('');
    const [feedbackStatus, setFeedbackStatus] = useState({ loading: false, type: '', message: '' });

    useEffect(() => {
        if (activeTab === 'dashboard') {
            fetchDashboardData();
        } else if (activeTab === 'feedback') {
            fetchFeedbackData();
        }
    }, [activeTab]);

    const fetchDashboardData = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/api/dashboard');
            if (response.data.success) {
                setDatasets(response.data.data);
            } else {
                setError('Failed to fetch dashboard data');
            }
        } catch (err) {
            console.error(err);
            setError('Terjadi kesalahan saat mengambil data grafik dari server.');
        } finally {
            setLoading(false);
        }
    };

    // ----- FEEDBACK HANDLERS -----
    const fetchFeedbackData = async () => {
        try {
            setFeedbackStatus({ loading: true, type: '', message: '' });
            const response = await axios.get('/api/feedbackLink');
            if (response.data.success) {
                setFeedbackLink(response.data.feedback_link.link);
            }
        } catch (err) {
            console.error(err);
            setFeedbackStatus({ type: 'error', message: 'Gagal mengambil data link feedback.' });
        } finally {
            setFeedbackStatus((prev) => ({ ...prev, loading: false }));
        }
    };

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
        } catch (err) {
            console.error(err);
            const errorMessage =
                err.response?.data?.message || 'Gagal menyimpan link feedback. Pastikan URL valid.';
            setFeedbackStatus({ loading: false, type: 'error', message: errorMessage });
        }
    };

    // ----- UPLOAD EXCEL HANDLERS -----

    const handleFileChange = (e) => {
        setSelectedFile(e.target.files[0]);
        setPreviewData(null);
        setUploadStatus({ loading: false, type: '', message: '' });
    };

    const handlePreview = async () => {
        if (!selectedFile) return;
        setUploadStatus({ loading: true, type: '', message: 'Sedang membaca file...' });

        const formData = new FormData();
        formData.append('excel_file', selectedFile);

        try {
            const response = await axios.post('/api/dashboard/extract', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (response.data.success) {
                setPreviewData(response.data.datasets);
                setUploadStatus({
                    loading: false,
                    type: 'success',
                    message: 'Preview data berhasil dimuat.',
                });
            }
        } catch (err) {
            setUploadStatus({
                loading: false,
                type: 'error',
                message: err.response?.data?.message || 'Gagal membaca preview file Excel.',
            });
        }
    };

    const handlePushData = async () => {
        if (!selectedFile) return;
        setUploadStatus({ loading: true, type: '', message: 'Sedang menyimpan ke database...' });

        const formData = new FormData();
        formData.append('excel_file', selectedFile);

        try {
            const response = await axios.post('/api/dashboard/pushdata', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (response.data.success) {
                setUploadStatus({
                    loading: false,
                    type: 'success',
                    message: 'Data grafik berhasil disimpan ke database!',
                });
                setSelectedFile(null);
                setPreviewData(null);
                document.getElementById('excel_upload_input').value = ''; // Reset input
            }
        } catch (err) {
            setUploadStatus({
                loading: false,
                type: 'error',
                message: err.response?.data?.message || 'Gagal menyimpan data ke database.',
            });
        }
    };

    const handleClearData = async () => {
        if (!window.confirm('Anda yakin ingin menghapus SEMUA data grafik di dashboard?')) return;

        setUploadStatus({ loading: true, type: '', message: 'Sedang menghapus data...' });
        try {
            const response = await axios.delete('/api/dashboard/cleardata');
            if (response.data.success) {
                setUploadStatus({
                    loading: false,
                    type: 'success',
                    message: 'Semua data grafik berhasil dihapus dari database.',
                });
                setDatasets([]); // Clear local state
            }
        } catch (err) {
            setUploadStatus({
                loading: false,
                type: 'error',
                message: 'Gagal menghapus data grafik.',
            });
        }
    };

    // ----- RENDERERS -----

    const COLORS = ['#9F1521', '#E53E3E', '#F6AD55', '#48BB78', '#4299E1', '#805AD5'];

    const renderChart = (dataset) => {
        const chartData = dataset.labels.map((label, index) => ({
            name: label,
            value: Number(dataset.values[index]),
        }));

        switch (dataset.chart_type.toLowerCase()) {
            case 'bar':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <BarChart
                            data={chartData}
                            margin={{ top: 20, right: 30, left: 20, bottom: 5 }}
                        >
                            <CartesianGrid strokeDasharray="3 3" opacity={0.2} vertical={false} />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} />
                            <YAxis axisLine={false} tickLine={false} />
                            <Tooltip
                                cursor={{ fill: '#f4f4f4' }}
                                contentStyle={{
                                    borderRadius: '8px',
                                    border: 'none',
                                    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)',
                                }}
                            />
                            <Legend />
                            <Bar
                                dataKey="value"
                                fill="#9F1521"
                                name={dataset.y_label || 'Nilai'}
                                radius={[4, 4, 0, 0]}
                                maxBarSize={50}
                            />
                        </BarChart>
                    </ResponsiveContainer>
                );
            case 'pie':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <PieChart>
                            <Pie
                                data={chartData}
                                cx="50%"
                                cy="50%"
                                labelLine={false}
                                label={({ name, percent }) =>
                                    `${name} (${(percent * 100).toFixed(0)}%)`
                                }
                                outerRadius={100}
                                innerRadius={60}
                                fill="#8884d8"
                                dataKey="value"
                            >
                                {chartData.map((entry, index) => (
                                    <Cell
                                        key={`cell-${index}`}
                                        fill={COLORS[index % COLORS.length]}
                                    />
                                ))}
                            </Pie>
                            <Tooltip
                                contentStyle={{
                                    borderRadius: '8px',
                                    border: 'none',
                                    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)',
                                }}
                            />
                            <Legend />
                        </PieChart>
                    </ResponsiveContainer>
                );
            case 'line':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <LineChart
                            data={chartData}
                            margin={{ top: 20, right: 30, left: 20, bottom: 5 }}
                        >
                            <CartesianGrid strokeDasharray="3 3" opacity={0.2} vertical={false} />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} />
                            <YAxis axisLine={false} tickLine={false} />
                            <Tooltip
                                contentStyle={{
                                    borderRadius: '8px',
                                    border: 'none',
                                    boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)',
                                }}
                            />
                            <Legend />
                            <Line
                                type="monotone"
                                dataKey="value"
                                stroke="#9F1521"
                                name={dataset.y_label || 'Nilai'}
                                strokeWidth={3}
                                dot={{ r: 4, fill: '#9F1521' }}
                                activeDot={{ r: 6 }}
                            />
                        </LineChart>
                    </ResponsiveContainer>
                );
            default:
                return (
                    <div className="flex h-full items-center justify-center text-gray-500">
                        Tipe grafik '{dataset.chart_type}' tidak didukung
                    </div>
                );
        }
    };

    const renderDashboardContent = () => (
        <>
            <div className="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div className="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                    <h3 className="mb-1 text-sm font-medium text-gray-500">Total Mahasiswa</h3>
                    <p className="text-4xl font-bold text-gray-800">1,234</p>
                </div>
                <div className="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                    <h3 className="mb-1 text-sm font-medium text-gray-500">Lulus Tepat Waktu</h3>
                    <p className="text-4xl font-bold text-green-600">85%</p>
                </div>
                <div className="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                    <h3 className="mb-1 text-sm font-medium text-gray-500">Pending Reservasi</h3>
                    <p className="text-4xl font-bold text-orange-500">5</p>
                </div>
            </div>

            <div className="min-h-[400px] rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                {loading ? (
                    <div className="flex h-full min-h-[300px] flex-col items-center justify-center text-gray-400">
                        <Loader2 className="mb-4 animate-spin text-[#9F1521]" size={40} />
                        <p className="font-medium text-gray-500">Memuat data grafik...</p>
                    </div>
                ) : error ? (
                    <div className="flex h-full min-h-[300px] flex-col items-center justify-center text-red-500">
                        <AlertCircle size={48} className="mb-4 opacity-80" />
                        <p className="font-medium">{error}</p>
                        <button
                            onClick={fetchDashboardData}
                            className="mt-4 rounded-lg bg-red-100 px-4 py-2 font-medium text-[#9F1521] transition-colors hover:bg-red-200"
                        >
                            Coba Lagi
                        </button>
                    </div>
                ) : datasets.length === 0 ? (
                    <div className="flex h-full min-h-[300px] flex-col items-center justify-center text-gray-400">
                        <FileText size={48} className="mx-auto mb-4 opacity-50" />
                        <p className="font-medium text-gray-500">Belum ada data grafik</p>
                        <p className="text-sm">
                            Silakan upload data Excel terlebih dahulu melalui menu Upload.
                        </p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-2">
                        {datasets.map((dataset) => (
                            <div
                                key={dataset.id}
                                className="rounded-xl border border-gray-100 p-6 shadow-sm transition-shadow hover:shadow-md"
                            >
                                <h2 className="mb-6 text-center text-lg font-bold text-gray-800">
                                    {dataset.title}
                                </h2>
                                {renderChart(dataset)}
                                <p className="mt-4 text-center text-sm font-medium text-gray-500">
                                    {dataset.x_label}
                                </p>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </>
    );

    const renderUploadContent = () => (
        <div className="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 className="mb-6 text-xl font-bold text-gray-800">Manajemen Data Dashboard</h2>

            <div className="mb-8 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-6">
                <div className="flex flex-col items-center">
                    <Upload size={48} className="mb-4 text-gray-400" />
                    <p className="mb-2 font-medium text-gray-600">
                        Pilih File Excel (.xlsx, .xls) untuk diunggah
                    </p>
                    <input
                        type="file"
                        id="excel_upload_input"
                        accept=".xlsx, .xls"
                        onChange={handleFileChange}
                        className="mb-4 block w-full max-w-sm text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#9F1521] hover:file:bg-red-100"
                    />

                    <div className="mt-2 flex gap-4">
                        <button
                            onClick={handlePreview}
                            disabled={!selectedFile || uploadStatus.loading}
                            className={`flex items-center gap-2 rounded-lg px-6 py-2 font-medium transition-colors ${!selectedFile || uploadStatus.loading ? 'cursor-not-allowed bg-gray-200 text-gray-400' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'}`}
                        >
                            <Eye size={18} /> Preview Data
                        </button>
                        <button
                            onClick={handlePushData}
                            disabled={!selectedFile || uploadStatus.loading}
                            className={`flex items-center gap-2 rounded-lg px-6 py-2 font-medium transition-colors ${!selectedFile || uploadStatus.loading ? 'cursor-not-allowed bg-gray-200 text-gray-400' : 'bg-[#9F1521] text-white hover:bg-red-800'}`}
                        >
                            <Upload size={18} /> Simpan ke Database
                        </button>
                    </div>
                </div>
            </div>

            {uploadStatus.message && (
                <div
                    className={`mb-6 flex items-center gap-3 rounded-lg p-4 ${uploadStatus.type === 'error' ? 'bg-red-50 text-red-700' : uploadStatus.type === 'success' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'}`}
                >
                    {uploadStatus.loading ? (
                        <Loader2 className="animate-spin" size={20} />
                    ) : uploadStatus.type === 'error' ? (
                        <AlertCircle size={20} />
                    ) : (
                        <CheckCircle size={20} />
                    )}
                    <span className="font-medium">{uploadStatus.message}</span>
                </div>
            )}

            {previewData && (
                <div className="mt-8 border-t border-gray-100 pt-8">
                    <h3 className="mb-4 flex items-center gap-2 text-lg font-bold text-gray-800">
                        <FileText className="text-[#9F1521]" /> Preview Data
                        <span className="rounded-full bg-gray-100 px-2 py-1 text-sm font-normal text-gray-500">
                            {previewData.length} Grafik Ditemukan
                        </span>
                    </h3>

                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        {previewData.map((data, index) => (
                            <div
                                key={index}
                                className="rounded-lg border border-gray-200 bg-gray-50 p-4"
                            >
                                <h4 className="font-bold text-gray-800">{data.title}</h4>
                                <div className="mt-2 grid grid-cols-2 gap-2 text-sm text-gray-600">
                                    <p>
                                        <span className="font-medium text-gray-500">Tipe:</span>{' '}
                                        {data.chart_type}
                                    </p>
                                    <p>
                                        <span className="font-medium text-gray-500">
                                            Jumlah Data:
                                        </span>{' '}
                                        {data.labels?.length || 0}
                                    </p>
                                    <p>
                                        <span className="font-medium text-gray-500">X-Axis:</span>{' '}
                                        {data.x_label || '-'}
                                    </p>
                                    <p>
                                        <span className="font-medium text-gray-500">Y-Axis:</span>{' '}
                                        {data.y_label || '-'}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            <div className="mt-12 border-t border-red-100 pt-6">
                <button
                    onClick={handleClearData}
                    disabled={uploadStatus.loading}
                    className="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-6 py-2 font-medium text-red-600 transition-colors hover:bg-red-600 hover:text-white"
                >
                    <Trash2 size={18} /> Hapus Semua Data Grafik
                </button>
            </div>
        </div>
    );

    const renderFeedbackContent = () => (
        <div className="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 className="mb-6 text-xl font-bold text-gray-800">Manajemen Tautan Feedback</h2>

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

    return (
        <div className="flex h-screen bg-gray-50">
            {/* Sidebar */}
            <div className="z-10 flex w-64 flex-col bg-[#9F1521] text-white shadow-xl">
                <div className="border-b border-red-800 p-6 text-2xl font-bold tracking-wide">
                    Info-BIF Admin
                </div>
                <nav className="mt-2 flex-1 space-y-2 p-4">
                    <button
                        onClick={() => setActiveTab('dashboard')}
                        className={`flex w-full items-center gap-3 rounded-lg p-3 transition-colors ${activeTab === 'dashboard' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <LayoutDashboard size={20} />
                        <span className="font-medium">Dashboard Statistik</span>
                    </button>
                    <button
                        onClick={() => setActiveTab('upload')}
                        className={`flex w-full items-center gap-3 rounded-lg p-3 transition-colors ${activeTab === 'upload' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <Upload size={20} />
                        <span className="font-medium">Upload Data Excel</span>
                    </button>
                    <button
                        onClick={() => setActiveTab('feedback')}
                        className={`flex w-full items-center gap-3 rounded-lg p-3 transition-colors ${activeTab === 'feedback' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <MessageSquare size={20} />
                        <span className="font-medium">Manajemen Feedback</span>
                    </button>
                    <button
                        onClick={() => setActiveTab('reservation')}
                        className={`flex w-full items-center gap-3 rounded-lg p-3 transition-colors ${activeTab === 'reservation' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <Calendar size={20} />
                        <span className="font-medium">Approval Reservasi</span>
                    </button>
                </nav>
            </div>

            {/* Main Content */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Header */}
                <header className="z-0 flex items-center justify-between bg-white p-4 px-8 shadow-sm">
                    <h1 className="text-2xl font-semibold text-gray-800">
                        {activeTab === 'dashboard'
                            ? 'Dashboard Statistik'
                            : activeTab === 'upload'
                              ? 'Upload Data Excel'
                              : activeTab === 'feedback'
                                ? 'Manajemen Feedback'
                                : 'Approval Reservasi'}
                    </h1>
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
                    {activeTab === 'dashboard' && renderDashboardContent()}
                    {activeTab === 'upload' && renderUploadContent()}
                    {activeTab === 'feedback' && renderFeedbackContent()}
                    {activeTab === 'reservation' && (
                        <div className="flex min-h-[400px] flex-col items-center justify-center rounded-xl border border-gray-100 bg-white p-6 text-gray-400 shadow-sm">
                            <Calendar size={48} className="mx-auto mb-4 opacity-50" />
                            <p className="font-medium text-gray-500">Fitur Approval Reservasi</p>
                            <p className="text-sm">
                                Fitur ini akan segera tersedia di pembaruan selanjutnya.
                            </p>
                        </div>
                    )}
                </main>
            </div>
        </div>
    );
}
