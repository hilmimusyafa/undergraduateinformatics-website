import { ArticleContainer } from '@/components/ArticleContainer';
import { TextLink } from '@/components/TextLink';

import { type Tag } from './types';

export function TagsContent({ tags }: { tags: Tag[] }) {
    return (
        <ArticleContainer className="max-w-[37em]">
            <h1>Daftar Label</h1>
            <p className="text-muted-foreground">
                Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan
                label.
            </p>
            <ul>
                {tags.map((tag) => (
                    <li key={tag.id}>
                        <TextLink
                            variant="underline"
                            className="whitespace-normal no-underline"
                            to="/tags/$slug"
                            params={{ slug: tag.slug }}
                        >
                            {tag.name} ({tag.posts_count})
                        </TextLink>
                        {tag.description && (
                            <p className="text-muted-foreground">{tag.description}</p>
                        )}
                    </li>
                ))}
            </ul>
        </ArticleContainer>
    );
}
