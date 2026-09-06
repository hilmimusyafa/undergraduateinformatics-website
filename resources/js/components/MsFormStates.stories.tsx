import type { Story } from '@ladle/react';

import { MsFormError, MsFormSkeleton, MsFormSuccess, MsFormUnavailable } from './MsFormStates';

export default {
    title: 'Microsoft Form/States',
};

export const Loading: Story = () => <MsFormSkeleton />;

export const Unavailable: Story = () => <MsFormUnavailable />;

export const LoadError: Story = () => <MsFormError />;

export const Success: Story = () => <MsFormSuccess onReset={() => undefined} />;
