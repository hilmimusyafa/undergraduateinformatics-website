import type { Story, StoryDefault } from '@ladle/react';

import { PieChart } from './pie-chart';

const labels = ['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Banten', 'DKI Jakarta'];
const values = [320, 148, 122, 76, 58];

export default {
    title: 'Charts/PieChart',
} satisfies StoryDefault;

export const Default: Story = () => <PieChart labels={labels} values={values} />;
Default.meta = { width: 'large' };

export const Mobile: Story = () => <PieChart labels={labels} values={values} />;
Mobile.meta = { width: 'small' };
