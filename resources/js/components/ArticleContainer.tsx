import { type ComponentProps } from 'react';

import { cn } from '../lib/utils';

type ArticleContainerProps = ComponentProps<'div'>;

export function ArticleContainer({ className, ...divProps }: ArticleContainerProps) {
    return (
        <div
            className={cn(
                'typeset typeset-article mx-auto w-full max-w-[37em] py-10 md:py-9',
                className
            )}
            {...divProps}
        />
    );
}
