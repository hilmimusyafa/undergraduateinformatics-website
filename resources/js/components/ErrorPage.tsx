import { ArticleContainer } from './ArticleContainer';

interface ErrorPageProps {
    error?: { message?: string };
}

export function ErrorPage({ error }: ErrorPageProps) {
    return (
        <ArticleContainer>
            <h1 className="text-foreground text-3xl leading-tight font-semibold">
                Terjadi kesalahan
            </h1>
            <p className="text-muted-foreground mt-4.5 md:mt-4">
                Maaf, ada yang tidak beres. Silakan muat ulang halaman ini.
            </p>
            {error?.message && (
                <p className="text-muted-foreground mt-4.5 md:mt-4">{error.message}</p>
            )}
        </ArticleContainer>
    );
}
