import { createFileRoute } from '@tanstack/react-router';
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Search, Edit, Trash2, X, Loader2, Image as ImageIcon } from 'lucide-react';

export const Route = createFileRoute('/admin/informasi')({
    component: AdminInformasi,
});

function AdminInformasi() {
    const [informasiList, setInformasiList] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    
    // Modal State
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

    useEffect(() => {
        fetchInformasi();
    }, []);

    const fetchInformasi = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/api/informasi');
            // Depending on the exact API structure. Let's assume response.data.data
            if (response.data) {
                setInformasiList(response.data.data || response.data);
            }
        } catch (error) {
            console.error('Failed to fetch informasi', error);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenModal = (info = null) => {
        if (info) {
            setIsEditing(true);
            setFormData({
                id: info.id,
                title: info.title || info.judul || '',
                subtitle: info.subtitle || info.sub_judul || '',
                description: info.description || info.deskripsi || '',
                tag_id: info.tag_id || '',
                image: null, // file inputs can't be set programmatically like this
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
                // Laravel typically uses POST with _method=PUT or POST /update/{id} for multipart/form-data
                await axios.post(`/api/informasi/update/${formData.id}`, payload, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            } else {
                await axios.post('/api/informasi/store', payload, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            }
            setIsModalOpen(false);
            fetchInformasi();
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
                fetchInformasi();
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
        <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 min-h-[500px]">
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <h2 className="text-2xl font-bold text-gray-800">Manajemen Informasi</h2>
                <div className="flex items-center gap-4 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                        <input 
                            type="text" 
                            placeholder="Cari informasi..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9F1521] focus:border-[#9F1521] outline-none"
                        />
                    </div>
                    <button 
                        onClick={() => handleOpenModal()}
                        className="flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors whitespace-nowrap"
                    >
                        <Plus size={18} /> Tambah Informasi
                    </button>
                </div>
            </div>

            {loading ? (
                <div className="flex justify-center items-center h-64 text-[#9F1521]">
                    <Loader2 className="animate-spin" size={40} />
                </div>
            ) : (
                <div className="overflow-x-auto">
                    <table className="w-full border-collapse">
                        <thead>
                            <tr className="border-b-2 border-gray-200">
                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Sub-Judul</th>
                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Deskripsi</th>
                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Gambar</th>
                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Tag</th>
                                <th className="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredList.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="text-center py-8 text-gray-500">
                                        Tidak ada data
                                    </td>
                                </tr>
                            ) : (
                                filteredList.map((item: any) => (
                                    <tr key={item.id} className="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td className="py-3 px-4 text-gray-800 font-medium">{item.title || item.judul}</td>
                                        <td className="py-3 px-4 text-gray-600">{item.subtitle || item.sub_judul}</td>
                                        <td className="py-3 px-4 text-gray-600 truncate max-w-xs">{item.description || item.deskripsi}</td>
                                        <td className="py-3 px-4">
                                            {(item.image || item.gambar) ? (
                                                <div className="w-12 h-12 bg-gray-200 rounded overflow-hidden">
                                                    <img src={`/storage/${item.image || item.gambar}`} alt={item.title || item.judul} className="w-full h-full object-cover" />
                                                </div>
                                            ) : (
                                                <div className="w-12 h-12 bg-gray-100 flex items-center justify-center text-gray-400 rounded">
                                                    <ImageIcon size={20} />
                                                </div>
                                            )}
                                        </td>
                                        <td className="py-3 px-4 text-gray-600">
                                            <span className="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-medium border border-blue-100">
                                                {item.tag?.name || item.tag_id || '-'}
                                            </span>
                                        </td>
                                        <td className="py-3 px-4">
                                            <div className="flex items-center justify-center gap-2">
                                                <button 
                                                    onClick={() => handleOpenModal(item)}
                                                    className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                >
                                                    <Edit size={18} />
                                                </button>
                                                <button 
                                                    onClick={() => handleDelete(item.id)}
                                                    className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
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

            {/* Modal Form */}
            {isModalOpen && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
                        <div className="p-6 border-b flex justify-between items-center">
                            <h3 className="text-xl font-bold text-gray-800">
                                {isEditing ? 'Edit Informasi' : 'Tambah Informasi'}
                            </h3>
                            <button onClick={handleCloseModal} className="text-gray-400 hover:text-gray-600">
                                <X size={24} />
                            </button>
                        </div>
                        
                        <form onSubmit={handleSubmit} className="p-6 overflow-y-auto flex-1 space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                                <input 
                                    type="text" 
                                    required
                                    value={formData.title}
                                    onChange={(e) => setFormData({...formData, title: e.target.value})}
                                    className="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Sub-Judul</label>
                                <input 
                                    type="text" 
                                    value={formData.subtitle}
                                    onChange={(e) => setFormData({...formData, subtitle: e.target.value})}
                                    className="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Tag ID</label>
                                <input 
                                    type="number" 
                                    value={formData.tag_id}
                                    onChange={(e) => setFormData({...formData, tag_id: e.target.value})}
                                    className="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                                <textarea 
                                    required
                                    rows={4}
                                    value={formData.description}
                                    onChange={(e) => setFormData({...formData, description: e.target.value})}
                                    className="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none resize-none"
                                ></textarea>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Gambar {isEditing && '(Kosongkan jika tidak ingin mengubah)'}
                                </label>
                                <input 
                                    type="file" 
                                    accept="image/*"
                                    onChange={handleFileChange}
                                    required={!isEditing}
                                    className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                />
                            </div>
                            
                            <div className="pt-4 border-t flex justify-end gap-3 mt-6">
                                <button 
                                    type="button" 
                                    onClick={handleCloseModal}
                                    className="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    disabled={formLoading}
                                    className="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 disabled:opacity-50"
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
