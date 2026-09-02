import type { Story } from '@ladle/react';

import { ErrorPage } from './ErrorPage';

export default {
    title: 'Status Pages/ErrorPage',
};

export const Default: Story = () => <ErrorPage />;

export const WithError: Story = () => <ErrorPage error={{ message: 'Contoh pesan kesalahan' }} />;
