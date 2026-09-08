import type { Story, StoryDefault } from '@ladle/react';

import { ChartCard } from './ChartCard';
import { type DashboardDataset } from './types';

const barDataset: DashboardDataset = {
    id: 1,
    title: 'Mahasiswa per Angkatan',
    chart_type: 'bar',
    x_label: 'Angkatan',
    y_label: 'Jumlah',
    labels: ['2022', '2023', '2024', '2025'],
    values: [240, 265, 289, 312],
};

const pieDataset: DashboardDataset = {
    id: 2,
    title: 'Mahasiswa per Provinsi',
    chart_type: 'pie',
    x_label: 'Provinsi',
    y_label: 'Jumlah',
    labels: ['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Banten'],
    values: [320, 148, 122, 76],
};

const lineDataset: DashboardDataset = {
    id: 3,
    title: 'Pertumbuhan Mahasiswa per Tahun',
    chart_type: 'line',
    x_label: 'Tahun',
    y_label: 'Jumlah',
    labels: ['2019', '2020', '2021', '2022', '2023', '2024', '2025'],
    values: [180, 205, 228, 240, 265, 289, 312],
};

export default {
    title: 'Charts/ChartCard',
} satisfies StoryDefault;

export const Bar: Story = () => (
    <div className="mx-auto w-full max-w-xl p-4">
        <ChartCard dataset={barDataset} />
    </div>
);
Bar.meta = { width: 'large' };

export const Pie: Story = () => (
    <div className="mx-auto w-full max-w-xl p-4">
        <ChartCard dataset={pieDataset} />
    </div>
);
Pie.meta = { width: 'large' };

export const Line: Story = () => (
    <div className="mx-auto w-full max-w-xl p-4">
        <ChartCard dataset={lineDataset} />
    </div>
);
Line.meta = { width: 'large' };
