import { type QueryClient } from '@tanstack/react-query';
import { HeadContent, Outlet, createRootRouteWithContext } from '@tanstack/react-router';

export const Route = createRootRouteWithContext<{ queryClient: QueryClient }>()({
    component: RootLayout,
});

function RootLayout() {
    return (
        <>
            <HeadContent />
            <Outlet />
        </>
    );
}
