import type { Story } from '@ladle/react';

import { DashboardChartsSkeleton } from './DashboardChartsStates';

export default {
    title: 'Charts/DashboardCharts/States',
};

export const Loading: Story = () => <DashboardChartsSkeleton />;
Loading.meta = { width: 'large' };

export const LoadingMobile: Story = () => <DashboardChartsSkeleton />;
LoadingMobile.meta = { width: 'small' };
