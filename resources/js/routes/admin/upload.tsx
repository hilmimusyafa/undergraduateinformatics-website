import { createFileRoute } from '@tanstack/react-router';
import React, { useState } from 'react';
import axios from 'axios';
import { Upload, FileText, Loader2, AlertCircle, CheckCircle, Trash2, Eye } from 'lucide-react';

export const Route = createFileRoute('/admin/upload')({
    component: AdminUpload,
});

function AdminUpload() {
    const [selectedFile, setSelectedFile] = useState<File | null>(null);
    const [previewData, setPreviewData] = useState<any>(null);
    const [uploadStatus, setUploadStatus] = useState({ loading: false, type: '', message: '' });

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files.length > 0) {
            setSelectedFile(e.target.files[0]);
            setPreviewData(null);
            setUploadStatus({ loading: false, type: '', message: '' });
        }
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
        } catch (err: any) {
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
                const input = document.getElementById('excel_upload_input') as HTMLInputElement;
                if (input) input.value = '';
            }
        } catch (err: any) {
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
            }
        } catch (err) {
            setUploadStatus({ 
                loading: false, 
                type: 'error', 
                message: 'Gagal menghapus data grafik.' 
            });
        }
    };

    return (
        <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 min-h-[500px]">
            <h2 className="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Manajemen Data Dashboard</h2>
            
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
                        {previewData.map((data: any, index: number) => (
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
}
