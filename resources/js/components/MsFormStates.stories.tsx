import type { Story } from '@ladle/react';

import { MsFormError, MsFormSuccess, MsFormUnavailable } from './MsFormStates';

export default {
    title: 'Microsoft Form/States',
};

export const Unavailable: Story = () => <MsFormUnavailable />;

export const LoadError: Story = () => <MsFormError />;

export const Success: Story = () => <MsFormSuccess onReset={() => undefined} />;
