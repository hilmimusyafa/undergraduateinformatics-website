import { createLazyFileRoute } from '@tanstack/react-router';

import { ReservationPage } from '@/features/reservation/ReservationPage';

export const Route = createLazyFileRoute('/_public/reservation')({
    component: ReservationPage,
});
