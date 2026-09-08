import type { Story } from '@ladle/react';

import { ErrorState } from '@/components/ErrorState';

import { HomeSkeleton } from './HomeStates';

export default {
    title: 'Home/States',
};

export const Loading: Story = () => <HomeSkeleton />;
Loading.meta = { width: 'large' };

export const LoadingMobile: Story = () => <HomeSkeleton />;
LoadingMobile.meta = { width: 'medium' };

export const Error: Story = () => <ErrorState />;
Error.meta = { width: 'large' };
