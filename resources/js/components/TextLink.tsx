import { type ComponentProps, forwardRef } from 'react';

import { createLink } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { type TextVariant, textButtonVariant } from './text-variants';
import { buttonVariants } from './ui/button';

type TextLinkBaseProps = ComponentProps<'a'> & {
    variant: TextVariant;
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
                    variant: textButtonVariant[variant],
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
