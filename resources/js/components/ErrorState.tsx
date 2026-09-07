import { ArticleContainer } from '@/components/ArticleContainer';

export function ErrorState() {
    return (
        <ArticleContainer>
            <p role="alert" className="text-muted-foreground">
                Terjadi kesalahan saat memuat halaman. Silakan coba lagi.
            </p>
        </ArticleContainer>
    );
}
