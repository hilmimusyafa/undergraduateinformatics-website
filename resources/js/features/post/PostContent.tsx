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
        <div className="mx-auto w-full max-w-[37em] py-[39.375px] md:py-8.75">
            <article className="typeset typeset-article">
                <h1>{post.title}</h1>
                <p className="text-muted-foreground mt-2 text-[22.5px] leading-[1.4] md:mt-2 md:text-[20px]">
                    {post.subtitle}
                </p>
                {post.image && (
                    <img
                        src={post.image}
                        alt={post.title}
                        className="mt-6 w-full"
                        onError={(event) => {
                            event.currentTarget.style.display = 'none';
                        }}
                    />
                )}
                <RichText className="typeset-muted mt-[39.375px] md:mt-[35px]" html={post.body} />
                <div className="mt-[22.5px] flex flex-wrap items-center gap-3 md:mt-5">
                    <p className="text-muted-foreground my-0 text-lg leading-[31.5px] md:text-base md:leading-7">
                        {isUpdated(post.created_at, post.updated_at) && 'Diperbarui '}
                        {format(post.updated_at, 'd MMM yyyy', { locale: id })}
                    </p>
                    {post.tags.length > 0 && (
                        <div className="flex flex-wrap items-center gap-3">
                            {post.tags.map((tag) => (
                                <TextLink
                                    key={tag.id}
                                    variant="fade"
                                    to="/tags/$slug"
                                    params={{ slug: tag.slug }}
                                    className="text-muted-foreground hover:text-foreground inline text-lg no-underline md:text-base"
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
