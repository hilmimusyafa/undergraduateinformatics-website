import { type ComponentProps } from 'react';

import { cn } from '../lib/utils';

type ArticleContainerProps = ComponentProps<'div'>;

export function ArticleContainer({ className, ...divProps }: ArticleContainerProps) {
    return (
        <div
            className={cn(
                'typeset typeset-article mx-auto w-full max-w-[37em] py-[39.375px] md:py-8.75',
                className
            )}
            {...divProps}
        />
    );
}
