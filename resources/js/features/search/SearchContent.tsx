import { ArticleContainer } from '@/components/ArticleContainer';
import { PostCard } from '@/components/PostCard';
import { SearchBar } from '@/components/SearchBar';
import { TextButton } from '@/components/TextButton';

import { type SearchResult } from './types';

interface SearchContentProps {
    q: string;
    result: SearchResult;
    onSearch: (value: string) => void;
    onPageChange: (page: number) => void;
}

export function SearchContent({ q, result, onSearch, onPageChange }: SearchContentProps) {
    const { posts, meta } = result;
    const hasQuery = q.trim().length > 0;

    return (
        <ArticleContainer>
            <SearchBar
                key={q ?? 'no-query'}
                defaultValue={q}
                variant="underline"
                className="w-full"
                onSubmit={onSearch}
            />
            {hasQuery ? (
                <p className="text-muted-foreground mt-5.5 md:mt-5">{meta.total} hasil</p>
            ) : (
                <p className="text-muted-foreground mt-5.5 md:mt-5">
                    Masukkan kata kunci untuk mencari.
                </p>
            )}

            {posts.length === 0 ? (
                <p role="status" className="text-muted-foreground mt-5.5 md:mt-5">
                    {hasQuery ? `Tidak ada hasil untuk “${q}”` : 'Belum ada informasi.'}
                </p>
            ) : (
                <>
                    <div className="mt-5.5 md:mt-5 flex flex-col gap-4">
                        {posts.map((post) => (
                            <PostCard key={post.id} post={post} />
                        ))}
                    </div>
                    <div className="mt-5.5 md:mt-5 grid grid-cols-[1fr_auto_1fr] items-center">
                        <TextButton
                            variant="fade"
                            className="justify-self-start border-0"
                            disabled={meta.current_page <= 1}
                            onClick={() => onPageChange(meta.current_page - 1)}
                        >
                            Kembali
                        </TextButton>
                        <span className="text-muted-foreground text-lg md:text-base">
                            {meta.current_page} dari {meta.last_page}
                        </span>
                        <TextButton
                            variant="fade"
                            className="justify-self-end border-0"
                            disabled={meta.current_page >= meta.last_page}
                            onClick={() => onPageChange(meta.current_page + 1)}
                        >
                            Lanjut
                        </TextButton>
                    </div>
                </>
            )}
        </ArticleContainer>
    );
}
