import { ErrorState } from '@/components/ErrorState';

import { HomeContent } from './HomeContent';
import { HomeSkeleton } from './HomeStates';
import { useHome } from './useHome';

export function HomePage() {
    const query = useHome();

    if (query.isPending) {
        return <HomeSkeleton />;
    }

    if (query.isError) {
        return <ErrorState />;
    }

    return <HomeContent data={query.data} />;
}
