import { format } from 'date-fns';
import { id } from 'date-fns/locale';

import { RichText } from '@/components/RichText';
import { TextLink } from '@/components/TextLink';
import { type Post } from '@/types/post';

import { isUpdated } from './types';

interface PostContentProps {
    post: Post;
}

export function PostContent({ post }: PostContentProps) {
    return (
        <div className="mx-auto w-full max-w-[37em] py-10 md:py-9">
            <article className="typeset typeset-article">
                <h1>{post.title}</h1>
                <h2 className="text-muted-foreground mt-2 md:mt-2">{post.subtitle}</h2>
                {post.image && (
                    <img
                        src={post.image}
                        alt={post.title}
                        className="mt-8 max-h-[37em] w-full rounded-md object-contain"
                        onError={(event) => {
                            event.currentTarget.style.display = 'none';
                        }}
                    />
                )}
                <RichText className="typeset-muted mt-12" html={post.body} />
                <div className="mt-12 flex flex-wrap items-center gap-2 md:mt-12">
                    <p className="text-muted-foreground my-0 text-base md:text-sm">
                        {isUpdated(post.created_at, post.updated_at) && 'Diperbarui '}
                        {format(post.updated_at, 'd MMM yyyy', { locale: id })}
                    </p>
                    {post.tags.length > 0 && (
                        <div className="flex flex-wrap items-center gap-2">
                            {post.tags.map((tag) => (
                                <TextLink
                                    key={tag.id}
                                    variant="fade"
                                    to="/tags/$slug"
                                    params={{ slug: tag.slug }}
                                    className="text-muted-foreground hover:text-foreground inline text-base no-underline md:text-sm"
                                >
                                    {tag.name}
                                </TextLink>
                            ))}
                        </div>
                    )}
                </div>
            </article>
        </div>
    );
}
