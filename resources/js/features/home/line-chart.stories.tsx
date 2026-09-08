import type { Story, StoryDefault } from '@ladle/react';

import { LineChart } from './line-chart';

const labels = ['2019', '2020', '2021', '2022', '2023', '2024', '2025'];
const values = [180, 205, 228, 240, 265, 289, 312];

export default {
    title: 'Charts/LineChart',
} satisfies StoryDefault;

export const Default: Story = () => <LineChart labels={labels} values={values} />;
Default.meta = { width: 'large' };

export const Mobile: Story = () => <LineChart labels={labels} values={values} />;
Mobile.meta = { width: 'small' };
