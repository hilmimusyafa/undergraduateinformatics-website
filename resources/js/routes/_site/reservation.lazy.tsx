import { createLazyFileRoute } from '@tanstack/react-router';

import { ReservationPage } from '@/features/reservation/ReservationPage';

export const Route = createLazyFileRoute('/_site/reservation')({
    component: ReservationPage,
});
