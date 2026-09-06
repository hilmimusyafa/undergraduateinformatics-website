import React, { useState } from 'react';

import { createLazyFileRoute } from '@tanstack/react-router';

import axios from 'axios';
import { AlertCircle, CheckCircle, Eye, FileText, Loader2, Trash2, Upload } from 'lucide-react';

export const Route = createLazyFileRoute('/admin/upload')({
    component: Component,
});

export function Component() {
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
        } catch (err: any) {
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
                const input = document.getElementById('excel_upload_input') as HTMLInputElement;
                if (input) input.value = '';
            }
        } catch (err: any) {
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
            }
        } catch {
            setUploadStatus({
                loading: false,
                type: 'error',
                message: 'Gagal menghapus data grafik.',
            });
        }
    };

    return (
        <div className="min-h-[500px] rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 className="mb-6 border-b pb-2 text-xl font-bold text-gray-800">
                Manajemen Data Dashboard
            </h2>

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
                        <FileText className="text-[#9F1521]" /> Preview Data{' '}
                        <span className="rounded-full bg-gray-100 px-2 py-1 text-sm font-normal text-gray-500">
                            {previewData.length} Grafik Ditemukan
                        </span>
                    </h3>

                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        {previewData.map((data: any, index: number) => (
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
}
