import { type ComponentProps, forwardRef } from 'react';

import { createLink } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

type TextLinkBaseProps = ComponentProps<'a'> & {
    variant: 'fade' | 'underline';
};

const TextLinkBase = forwardRef<HTMLAnchorElement, TextLinkBaseProps>(function TextLinkBase(
    { className, variant, ...props },
    ref
) {
    return (
        <a
            ref={ref}
            className={cn(
                buttonVariants({
                    variant: variant === 'underline' ? 'link' : 'ghost',
                }),
                'h-auto p-0 text-base',
                variant === 'fade' &&
                    'text-foreground hover:text-muted-foreground hover:bg-transparent dark:hover:bg-transparent',
                className
            )}
            {...props}
        />
    );
});

export const TextLink = createLink(TextLinkBase);
