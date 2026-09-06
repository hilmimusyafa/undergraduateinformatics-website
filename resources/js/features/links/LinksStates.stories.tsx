import type { Story } from '@ladle/react';

import { LinksContent } from './LinksContent';
import { LinksError, LinksSkeleton } from './LinksStates';

export default {
    title: 'Links/States',
};

export const Loading: Story = () => <LinksSkeleton />;
Loading.meta = { width: 'large' };

export const LoadingMobileTablet: Story = () => <LinksSkeleton />;
LoadingMobileTablet.meta = { width: 'medium' };

export const Empty: Story = () => <LinksContent sections={[]} />;
Empty.meta = { width: 'large' };

export const Error: Story = () => <LinksError />;
Error.meta = { width: 'large' };
