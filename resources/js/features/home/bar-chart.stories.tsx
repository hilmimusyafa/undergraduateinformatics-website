import type { Story, StoryDefault } from '@ladle/react';

import { BarChart } from './bar-chart';

const labels = ['2022', '2023', '2024', '2025'];
const values = [240, 265, 289, 312];

const longLabels = [
    'Sistem Informasi Akademik',
    'Sistem Informasi Perpustakaan',
    'Sistem Informasi Keuangan',
    'Sistem Informasi Alumni',
];

export default {
    title: 'Charts/BarChart',
} satisfies StoryDefault;

export const Default: Story = () => <BarChart labels={labels} values={values} />;
Default.meta = { width: 'large' };

export const LongLabels: Story = () => <BarChart labels={longLabels} values={values} />;
LongLabels.meta = { width: 'large' };

export const Mobile: Story = () => <BarChart labels={labels} values={values} />;
Mobile.meta = { width: 'small' };
