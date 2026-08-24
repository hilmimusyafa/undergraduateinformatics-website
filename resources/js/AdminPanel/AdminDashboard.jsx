import React, { useState, useEffect } from 'react';
import { LayoutDashboard, Users, Calendar, Upload, FileText, Loader2, AlertCircle, CheckCircle, Trash2, Eye, MessageSquare } from 'lucide-react';
import axios from 'axios';
import { 
    BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer,
    PieChart, Pie, Cell, LineChart, Line
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
        } catch (err) {
            console.error(err);
            const errorMessage = err.response?.data?.message || 'Gagal menyimpan link feedback. Pastikan URL valid.';
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
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            if (response.data.success) {
                setPreviewData(response.data.datasets);
                setUploadStatus({ loading: false, type: 'success', message: 'Preview data berhasil dimuat.' });
            }
        } catch (err) {
            setUploadStatus({ 
                loading: false, 
                type: 'error', 
                message: err.response?.data?.message || 'Gagal membaca preview file Excel.' 
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
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            if (response.data.success) {
                setUploadStatus({ loading: false, type: 'success', message: 'Data grafik berhasil disimpan ke database!' });
                setSelectedFile(null);
                setPreviewData(null);
                document.getElementById('excel_upload_input').value = ''; // Reset input
            }
        } catch (err) {
            setUploadStatus({ 
                loading: false, 
                type: 'error', 
                message: err.response?.data?.message || 'Gagal menyimpan data ke database.' 
            });
        }
    };

    const handleClearData = async () => {
        if (!window.confirm("Anda yakin ingin menghapus SEMUA data grafik di dashboard?")) return;
        
        setUploadStatus({ loading: true, type: '', message: 'Sedang menghapus data...' });
        try {
            const response = await axios.delete('/api/dashboard/cleardata');
            if (response.data.success) {
                setUploadStatus({ loading: false, type: 'success', message: 'Semua data grafik berhasil dihapus dari database.' });
                setDatasets([]); // Clear local state
            }
        } catch (err) {
            setUploadStatus({ 
                loading: false, 
                type: 'error', 
                message: 'Gagal menghapus data grafik.' 
            });
        }
    };

    // ----- RENDERERS -----

    const COLORS = ['#9F1521', '#E53E3E', '#F6AD55', '#48BB78', '#4299E1', '#805AD5'];

    const renderChart = (dataset) => {
        const chartData = dataset.labels.map((label, index) => ({
            name: label,
            value: Number(dataset.values[index])
        }));

        switch (dataset.chart_type.toLowerCase()) {
            case 'bar':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <BarChart data={chartData} margin={{ top: 20, right: 30, left: 20, bottom: 5 }}>
                            <CartesianGrid strokeDasharray="3 3" opacity={0.2} vertical={false} />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} />
                            <YAxis axisLine={false} tickLine={false} />
                            <Tooltip cursor={{fill: '#f4f4f4'}} contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }} />
                            <Legend />
                            <Bar dataKey="value" fill="#9F1521" name={dataset.y_label || 'Nilai'} radius={[4, 4, 0, 0]} maxBarSize={50} />
                        </BarChart>
                    </ResponsiveContainer>
                );
            case 'pie':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <PieChart>
                            <Pie 
                                data={chartData} 
                                cx="50%" cy="50%" 
                                labelLine={false}
                                label={({name, percent}) => `${name} (${(percent * 100).toFixed(0)}%)`}
                                outerRadius={100} 
                                innerRadius={60}
                                fill="#8884d8" 
                                dataKey="value"
                            >
                                {chartData.map((entry, index) => (
                                    <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                                ))}
                            </Pie>
                            <Tooltip contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }} />
                            <Legend />
                        </PieChart>
                    </ResponsiveContainer>
                );
            case 'line':
                return (
                    <ResponsiveContainer width="100%" height={300}>
                        <LineChart data={chartData} margin={{ top: 20, right: 30, left: 20, bottom: 5 }}>
                            <CartesianGrid strokeDasharray="3 3" opacity={0.2} vertical={false} />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} />
                            <YAxis axisLine={false} tickLine={false} />
                            <Tooltip contentStyle={{ borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }} />
                            <Legend />
                            <Line type="monotone" dataKey="value" stroke="#9F1521" name={dataset.y_label || 'Nilai'} strokeWidth={3} dot={{r: 4, fill: '#9F1521'}} activeDot={{r: 6}} />
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
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <h3 className="text-gray-500 text-sm font-medium mb-1">Total Mahasiswa</h3>
                    <p className="text-4xl font-bold text-gray-800">1,234</p>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <h3 className="text-gray-500 text-sm font-medium mb-1">Lulus Tepat Waktu</h3>
                    <p className="text-4xl font-bold text-green-600">85%</p>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <h3 className="text-gray-500 text-sm font-medium mb-1">Pending Reservasi</h3>
                    <p className="text-4xl font-bold text-orange-500">5</p>
                </div>
            </div>

            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 min-h-[400px]">
                {loading ? (
                    <div className="h-full flex flex-col items-center justify-center text-gray-400 min-h-[300px]">
                        <Loader2 className="animate-spin mb-4 text-[#9F1521]" size={40} />
                        <p className="font-medium text-gray-500">Memuat data grafik...</p>
                    </div>
                ) : error ? (
                    <div className="h-full flex flex-col items-center justify-center text-red-500 min-h-[300px]">
                        <AlertCircle size={48} className="mb-4 opacity-80" />
                        <p className="font-medium">{error}</p>
                        <button 
                            onClick={fetchDashboardData}
                            className="mt-4 px-4 py-2 bg-red-100 text-[#9F1521] rounded-lg hover:bg-red-200 transition-colors font-medium"
                        >
                            Coba Lagi
                        </button>
                    </div>
                ) : datasets.length === 0 ? (
                    <div className="h-full flex flex-col items-center justify-center text-gray-400 min-h-[300px]">
                        <FileText size={48} className="mx-auto mb-4 opacity-50" />
                        <p className="font-medium text-gray-500">Belum ada data grafik</p>
                        <p className="text-sm">Silakan upload data Excel terlebih dahulu melalui menu Upload.</p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {datasets.map((dataset) => (
                            <div key={dataset.id} className="border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                                <h2 className="text-lg font-bold text-gray-800 mb-6 text-center">{dataset.title}</h2>
                                {renderChart(dataset)}
                                <p className="text-center text-sm text-gray-500 mt-4 font-medium">{dataset.x_label}</p>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </>
    );

    const renderUploadContent = () => (
        <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 className="text-xl font-bold text-gray-800 mb-6">Manajemen Data Dashboard</h2>
            
            <div className="mb-8 p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                <div className="flex flex-col items-center">
                    <Upload size={48} className="text-gray-400 mb-4" />
                    <p className="text-gray-600 font-medium mb-2">Pilih File Excel (.xlsx, .xls) untuk diunggah</p>
                    <input 
                        type="file" 
                        id="excel_upload_input"
                        accept=".xlsx, .xls"
                        onChange={handleFileChange}
                        className="block w-full max-w-sm text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#9F1521] hover:file:bg-red-100 mb-4"
                    />
                    
                    <div className="flex gap-4 mt-2">
                        <button 
                            onClick={handlePreview}
                            disabled={!selectedFile || uploadStatus.loading}
                            className={`flex items-center gap-2 px-6 py-2 rounded-lg font-medium transition-colors ${!selectedFile || uploadStatus.loading ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'}`}
                        >
                            <Eye size={18} /> Preview Data
                        </button>
                        <button 
                            onClick={handlePushData}
                            disabled={!selectedFile || uploadStatus.loading}
                            className={`flex items-center gap-2 px-6 py-2 rounded-lg font-medium transition-colors ${!selectedFile || uploadStatus.loading ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#9F1521] text-white hover:bg-red-800'}`}
                        >
                            <Upload size={18} /> Simpan ke Database
                        </button>
                    </div>
                </div>
            </div>

            {uploadStatus.message && (
                <div className={`p-4 rounded-lg mb-6 flex items-center gap-3 ${uploadStatus.type === 'error' ? 'bg-red-50 text-red-700' : uploadStatus.type === 'success' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'}`}>
                    {uploadStatus.loading ? <Loader2 className="animate-spin" size={20} /> : uploadStatus.type === 'error' ? <AlertCircle size={20} /> : <CheckCircle size={20} />}
                    <span className="font-medium">{uploadStatus.message}</span>
                </div>
            )}

            {previewData && (
                <div className="mt-8 border-t border-gray-100 pt-8">
                    <h3 className="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <FileText className="text-[#9F1521]" /> Preview Data 
                        <span className="text-sm font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{previewData.length} Grafik Ditemukan</span>
                    </h3>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {previewData.map((data, index) => (
                            <div key={index} className="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 className="font-bold text-gray-800">{data.title}</h4>
                                <div className="mt-2 text-sm text-gray-600 grid grid-cols-2 gap-2">
                                    <p><span className="font-medium text-gray-500">Tipe:</span> {data.chart_type}</p>
                                    <p><span className="font-medium text-gray-500">Jumlah Data:</span> {data.labels?.length || 0}</p>
                                    <p><span className="font-medium text-gray-500">X-Axis:</span> {data.x_label || '-'}</p>
                                    <p><span className="font-medium text-gray-500">Y-Axis:</span> {data.y_label || '-'}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            <div className="mt-12 pt-6 border-t border-red-100">
                <button 
                    onClick={handleClearData}
                    disabled={uploadStatus.loading}
                    className="flex items-center gap-2 px-6 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg font-medium transition-colors hover:bg-red-600 hover:text-white"
                >
                    <Trash2 size={18} /> Hapus Semua Data Grafik
                </button>
            </div>
        </div>
    );

    const renderFeedbackContent = () => (
        <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 className="text-xl font-bold text-gray-800 mb-6">Manajemen Tautan Feedback</h2>
            
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

    return (
        <div className="flex h-screen bg-gray-50">
            {/* Sidebar */}
            <div className="w-64 bg-[#9F1521] text-white flex flex-col shadow-xl z-10">
                <div className="p-6 text-2xl font-bold border-b border-red-800 tracking-wide">
                    Info-BIF Admin
                </div>
                <nav className="flex-1 p-4 space-y-2 mt-2">
                    <button 
                        onClick={() => setActiveTab('dashboard')}
                        className={`w-full flex items-center gap-3 p-3 rounded-lg transition-colors ${activeTab === 'dashboard' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <LayoutDashboard size={20} />
                        <span className="font-medium">Dashboard Statistik</span>
                    </button>
                    <button 
                        onClick={() => setActiveTab('upload')}
                        className={`w-full flex items-center gap-3 p-3 rounded-lg transition-colors ${activeTab === 'upload' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <Upload size={20} />
                        <span className="font-medium">Upload Data Excel</span>
                    </button>
                    <button 
                        onClick={() => setActiveTab('feedback')}
                        className={`w-full flex items-center gap-3 p-3 rounded-lg transition-colors ${activeTab === 'feedback' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <MessageSquare size={20} />
                        <span className="font-medium">Manajemen Feedback</span>
                    </button>
                    <button 
                        onClick={() => setActiveTab('reservation')}
                        className={`w-full flex items-center gap-3 p-3 rounded-lg transition-colors ${activeTab === 'reservation' ? 'bg-red-800 shadow-sm' : 'hover:bg-red-800'}`}
                    >
                        <Calendar size={20} />
                        <span className="font-medium">Approval Reservasi</span>
                    </button>
                </nav>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                {/* Header */}
                <header className="bg-white shadow-sm p-4 px-8 flex justify-between items-center z-0">
                    <h1 className="text-2xl font-semibold text-gray-800">
                        {activeTab === 'dashboard' ? 'Dashboard Statistik' : 
                         activeTab === 'upload' ? 'Upload Data Excel' : 
                         activeTab === 'feedback' ? 'Manajemen Feedback' : 'Approval Reservasi'}
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
                    {activeTab === 'dashboard' && renderDashboardContent()}
                    {activeTab === 'upload' && renderUploadContent()}
                    {activeTab === 'feedback' && renderFeedbackContent()}
                    {activeTab === 'reservation' && (
                        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 min-h-[400px] flex flex-col items-center justify-center text-gray-400">
                            <Calendar size={48} className="mx-auto mb-4 opacity-50" />
                            <p className="font-medium text-gray-500">Fitur Approval Reservasi</p>
                            <p className="text-sm">Fitur ini akan segera tersedia di pembaruan selanjutnya.</p>
                        </div>
                    )}
                </main>
            </div>
        </div>
    );
}
