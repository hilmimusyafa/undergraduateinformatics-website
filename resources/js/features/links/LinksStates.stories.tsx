import type { Story } from '@ladle/react';

import { LinksEmpty, LinksError, LinksSkeleton } from './LinksStates';

export default {
    title: 'Links/States',
};

export const Loading: Story = () => <LinksSkeleton />;
Loading.meta = { width: 'large' };

export const LoadingMobileTablet: Story = () => <LinksSkeleton />;
LoadingMobileTablet.meta = { width: 'medium' };

export const Empty: Story = () => <LinksEmpty />;
Empty.meta = { width: 'large' };

export const Error: Story = () => <LinksError />;
Error.meta = { width: 'large' };
