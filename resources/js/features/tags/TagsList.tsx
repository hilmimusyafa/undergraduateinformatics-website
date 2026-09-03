import { ArticleContainer } from '@/components/ArticleContainer';

import { type Tag } from './types';

export function TagsList({ tags }: { tags: Tag[] }) {
    return (
        <ArticleContainer>
            <h1>Daftar Label</h1>
            <p className="text-muted-foreground">
                Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan
                label.
            </p>
            <ul>
                {tags.map((tag) => (
                    <li key={tag.id}>
                        <a href={`/tags/${tag.slug}`} className="text-blue-600 no-underline">
                            {tag.name} ({tag.posts_count})
                        </a>
                        {tag.description && (
                            <p className="text-muted-foreground">{tag.description}</p>
                        )}
                    </li>
                ))}
            </ul>
        </ArticleContainer>
    );
}
