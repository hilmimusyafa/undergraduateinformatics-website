import { createFileRoute } from '@tanstack/react-router';
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Loader2, AlertCircle, FileText } from 'lucide-react';
import { 
    BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer,
    PieChart, Pie, Cell, LineChart, Line
} from 'recharts';

export const Route = createFileRoute('/admin/')({
    component: AdminDashboardIndex,
});

function AdminDashboardIndex() {
    const [datasets, setDatasets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchDashboardData();
    }, []);

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

    const COLORS = ['#9F1521', '#E53E3E', '#F6AD55', '#48BB78', '#4299E1', '#805AD5'];

    const renderChart = (dataset: any) => {
        const chartData = dataset.labels.map((label: string, index: number) => ({
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
                                {chartData.map((entry: any, index: number) => (
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

    return (
        <div className="space-y-6">
            <h2 className="text-xl font-bold text-gray-800 border-b pb-2">Dashboard Statistik</h2>
            
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
                        {datasets.map((dataset: any) => (
                            <div key={dataset.id} className="border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                                <h2 className="text-lg font-bold text-gray-800 mb-6 text-center">{dataset.title}</h2>
                                {renderChart(dataset)}
                                <p className="text-center text-sm text-gray-500 mt-4 font-medium">{dataset.x_label}</p>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
