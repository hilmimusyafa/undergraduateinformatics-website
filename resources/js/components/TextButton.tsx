import { type ComponentProps } from 'react';

import { cn } from '../lib/utils';
import { Button } from './ui/button';

type TextButtonProps = Omit<ComponentProps<typeof Button>, 'variant'> & {
    variant: 'fade' | 'underline';
};

export function TextButton({ className, variant, ...props }: TextButtonProps) {
    return (
        <Button
            variant={variant === 'underline' ? 'link' : 'ghost'}
            className={cn(
                'h-auto p-0 text-base',
                variant === 'fade' &&
                    'text-foreground hover:text-muted-foreground hover:bg-transparent dark:hover:bg-transparent',
                className
            )}
            {...props}
        />
    );
}
