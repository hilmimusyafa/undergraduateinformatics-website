import React, { useEffect, useState } from 'react';

import { createLazyFileRoute } from '@tanstack/react-router';

import axios from 'axios';
import { Edit, Image as ImageIcon, Loader2, Plus, Search, Trash2, X } from 'lucide-react';

export const Route = createLazyFileRoute('/admin/informasi')({
    component: Component,
});

export function Component() {
    const [informasiList, setInformasiList] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [formData, setFormData] = useState({
        id: null,
        title: '',
        subtitle: '',
        description: '',
        tag_id: '',
        image: null as File | null,
    });
    const [formLoading, setFormLoading] = useState(false);
    const [reloadKey, setReloadKey] = useState(0);

    useEffect(() => {
        const loadInformasi = async () => {
            try {
                const response = await axios.get('/api/informasi');
                if (response.data) {
                    setInformasiList(response.data.data || response.data);
                }
            } catch (error) {
                console.error('Failed to fetch informasi', error);
            }
            setLoading(false);
        };

        loadInformasi();
    }, [reloadKey]);

    const handleOpenModal = (info: any = null) => {
        if (info) {
            setIsEditing(true);
            setFormData({
                id: info.id,
                title: info.title || info.judul || '',
                subtitle: info.subtitle || info.sub_judul || '',
                description: info.description || info.deskripsi || '',
                tag_id: info.tag_id || '',
                image: null,
            });
        } else {
            setIsEditing(false);
            setFormData({
                id: null,
                title: '',
                subtitle: '',
                description: '',
                tag_id: '',
                image: null,
            });
        }
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files.length > 0) {
            setFormData({ ...formData, image: e.target.files[0] });
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setFormLoading(true);

        const payload = new FormData();
        payload.append('judul', formData.title);
        payload.append('sub_judul', formData.subtitle);
        payload.append('deskripsi', formData.description);
        payload.append('tag_id', formData.tag_id);
        if (formData.image) {
            payload.append('gambar', formData.image);
        }

        try {
            if (isEditing) {
                await axios.post(`/api/informasi/update/${formData.id}`, payload, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
            } else {
                await axios.post('/api/informasi/store', payload, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
            }
            setIsModalOpen(false);
            setReloadKey((key) => key + 1);
        } catch (error) {
            console.error('Failed to save', error);
            alert('Gagal menyimpan data.');
        } finally {
            setFormLoading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (window.confirm('Apakah Anda yakin ingin menghapus informasi ini?')) {
            try {
                await axios.delete(`/api/informasi/delete/${id}`);
                setReloadKey((key) => key + 1);
            } catch (error) {
                console.error('Failed to delete', error);
                alert('Gagal menghapus data.');
            }
        }
    };

    const filteredList = informasiList.filter((item: any) =>
        (item.title || item.judul || '').toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="min-h-[500px] rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <div className="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <h2 className="text-2xl font-bold text-gray-800">Manajemen Informasi</h2>
                <div className="flex w-full items-center gap-4 md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search
                            className="absolute top-1/2 left-3 -translate-y-1/2 text-gray-400"
                            size={18}
                        />
                        <input
                            type="text"
                            placeholder="Cari informasi..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 outline-none focus:border-[#9F1521] focus:ring-2 focus:ring-[#9F1521]"
                        />
                    </div>
                    <button
                        onClick={() => handleOpenModal()}
                        className="flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 whitespace-nowrap text-white transition-colors hover:bg-gray-600"
                    >
                        <Plus size={18} /> Tambah Informasi
                    </button>
                </div>
            </div>

            {loading ? (
                <div className="flex h-64 items-center justify-center text-[#9F1521]">
                    <Loader2 className="animate-spin" size={40} />
                </div>
            ) : (
                <div className="overflow-x-auto">
                    <table className="w-full border-collapse">
                        <thead>
                            <tr className="border-b-2 border-gray-200">
                                <th className="px-4 py-3 text-left font-semibold text-gray-700">
                                    Judul
                                </th>
                                <th className="px-4 py-3 text-left font-semibold text-gray-700">
                                    Sub-Judul
                                </th>
                                <th className="px-4 py-3 text-left font-semibold text-gray-700">
                                    Deskripsi
                                </th>
                                <th className="px-4 py-3 text-left font-semibold text-gray-700">
                                    Gambar
                                </th>
                                <th className="px-4 py-3 text-left font-semibold text-gray-700">
                                    Tag
                                </th>
                                <th className="px-4 py-3 text-center font-semibold text-gray-700">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredList.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="py-8 text-center text-gray-500">
                                        Tidak ada data
                                    </td>
                                </tr>
                            ) : (
                                filteredList.map((item: any) => (
                                    <tr
                                        key={item.id}
                                        className="border-b border-gray-100 transition-colors hover:bg-gray-50"
                                    >
                                        <td className="px-4 py-3 font-medium text-gray-800">
                                            {item.title || item.judul}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            {item.subtitle || item.sub_judul}
                                        </td>
                                        <td className="max-w-xs truncate px-4 py-3 text-gray-600">
                                            {item.description || item.deskripsi}
                                        </td>
                                        <td className="px-4 py-3">
                                            {item.image || item.gambar ? (
                                                <div className="h-12 w-12 overflow-hidden rounded bg-gray-200">
                                                    <img
                                                        src={`/storage/${item.image || item.gambar}`}
                                                        alt={item.title || item.judul}
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                            ) : (
                                                <div className="flex h-12 w-12 items-center justify-center rounded bg-gray-100 text-gray-400">
                                                    <ImageIcon size={20} />
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            <span className="rounded border border-blue-100 bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                                {item.tag?.name || item.tag_id || '-'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex items-center justify-center gap-2">
                                                <button
                                                    onClick={() => handleOpenModal(item)}
                                                    className="rounded-lg p-2 text-blue-600 transition-colors hover:bg-blue-50"
                                                >
                                                    <Edit size={18} />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(item.id)}
                                                    className="rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50"
                                                >
                                                    <Trash2 size={18} />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            )}

            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl bg-white shadow-xl">
                        <div className="flex items-center justify-between border-b p-6">
                            <h3 className="text-xl font-bold text-gray-800">
                                {isEditing ? 'Edit Informasi' : 'Tambah Informasi'}
                            </h3>
                            <button
                                onClick={handleCloseModal}
                                className="text-gray-400 hover:text-gray-600"
                            >
                                <X size={24} />
                            </button>
                        </div>

                        <form
                            onSubmit={handleSubmit}
                            className="flex-1 space-y-4 overflow-y-auto p-6"
                        >
                            <div>
                                <label className="mb-1 block text-sm font-medium text-gray-700">
                                    Judul *
                                </label>
                                <input
                                    type="text"
                                    required
                                    value={formData.title}
                                    onChange={(e) =>
                                        setFormData({ ...formData, title: e.target.value })
                                    }
                                    className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-gray-700">
                                    Sub-Judul
                                </label>
                                <input
                                    type="text"
                                    value={formData.subtitle}
                                    onChange={(e) =>
                                        setFormData({ ...formData, subtitle: e.target.value })
                                    }
                                    className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-gray-700">
                                    Tag ID
                                </label>
                                <input
                                    type="number"
                                    value={formData.tag_id}
                                    onChange={(e) =>
                                        setFormData({ ...formData, tag_id: e.target.value })
                                    }
                                    className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-gray-700">
                                    Deskripsi *
                                </label>
                                <textarea
                                    required
                                    rows={4}
                                    value={formData.description}
                                    onChange={(e) =>
                                        setFormData({ ...formData, description: e.target.value })
                                    }
                                    className="w-full resize-none rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>
                            <div>
                                <label className="mb-1 block text-sm font-medium text-gray-700">
                                    Gambar {isEditing && '(Kosongkan jika tidak ingin mengubah)'}
                                </label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={handleFileChange}
                                    required={!isEditing}
                                    className="w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-gray-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-100"
                                />
                            </div>

                            <div className="mt-6 flex justify-end gap-3 border-t pt-4">
                                <button
                                    type="button"
                                    onClick={handleCloseModal}
                                    className="rounded border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    disabled={formLoading}
                                    className="flex items-center gap-2 rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600 disabled:opacity-50"
                                >
                                    {formLoading && <Loader2 size={16} className="animate-spin" />}
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
