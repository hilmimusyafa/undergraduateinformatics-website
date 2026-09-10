import { HomeContent } from './HomeContent';
import { useHome } from './useHome';

export function HomePage() {
    const { data } = useHome();

    return <HomeContent data={data} />;
}
