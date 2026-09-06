import { ArticleContainer } from '@/components/ArticleContainer';

export function PageError() {
    return (
        <ArticleContainer>
            <p role="alert" className="text-muted-foreground">
                Terjadi kesalahan saat memuat halaman. Silakan coba lagi.
            </p>
        </ArticleContainer>
    );
}
