import type { Story, StoryDefault } from '@ladle/react';

import { ChartTooltip, type ChartTooltipItem } from './chart-tooltip';

const payload: ChartTooltipItem[] = [{ name: 'Mahasiswa', value: 312 }];

export default {
    title: 'Charts/Tooltip',
} satisfies StoryDefault;

export const Active: Story = () => (
    <div className="flex min-h-screen items-center justify-center">
        <ChartTooltip active label="2025" payload={payload} />
    </div>
);

export const Inactive: Story = () => (
    <div className="flex min-h-screen items-center justify-center">
        <ChartTooltip active={false} label="2025" payload={payload} />
    </div>
);

export const WithUnit: Story = () => (
    <div className="flex min-h-screen items-center justify-center">
        <ChartTooltip active label="2025" payload={payload} unit=" mahasiswa" />
    </div>
);
