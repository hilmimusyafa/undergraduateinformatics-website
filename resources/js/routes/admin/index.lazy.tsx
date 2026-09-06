import { useEffect, useState } from 'react';

import { createLazyFileRoute } from '@tanstack/react-router';

import axios from 'axios';
import { AlertCircle, FileText, Loader2 } from 'lucide-react';
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

export const Route = createLazyFileRoute('/admin/')({
    component: Component,
});

export function Component() {
    const [datasets, setDatasets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [reloadKey, setReloadKey] = useState(0);

    useEffect(() => {
        const loadDashboardData = async () => {
            try {
                const response = await axios.get('/api/dashboard');
                if (response.data.success) {
                    setDatasets(response.data.data);
                } else {
                    setError('Failed to fetch dashboard data');
                }
            } catch (err) {
                console.error(err);
                setError('Terjadi kesalahan saat mengambil data grafik dari server.');
            }
            setLoading(false);
        };

        loadDashboardData();
    }, [reloadKey]);

    const COLORS = ['#9F1521', '#E53E3E', '#F6AD55', '#48BB78', '#4299E1', '#805AD5'];

    const renderChart = (dataset: any) => {
        const chartData = dataset.labels.map((label: string, index: number) => ({
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
                                    `${name} (${((percent ?? 0) * 100).toFixed(0)}%)`
                                }
                                outerRadius={100}
                                innerRadius={60}
                                fill="#8884d8"
                                dataKey="value"
                            >
                                {chartData.map((_entry: any, index: number) => (
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
                                cursor={{ fill: '#f4f4f4' }}
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

    return (
        <div className="space-y-6">
            <h2 className="border-b pb-2 text-xl font-bold text-gray-800">Dashboard Statistik</h2>

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
                            onClick={() => {
                                setLoading(true);
                                setReloadKey((key) => key + 1);
                            }}
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
                        {datasets.map((dataset: any) => (
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
        </div>
    );
}
