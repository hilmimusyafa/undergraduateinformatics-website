import type { Story } from '@ladle/react';

import { ReservationSkeleton } from './ReservationStates';

export default {
    title: 'Reservasi/States',
};

export const Loading: Story = () => <ReservationSkeleton />;
