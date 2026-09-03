import { type ComponentProps } from 'react';

import { cn } from '../lib/utils';
import { type TextVariant, textButtonVariant } from './text-variants';
import { Button } from './ui/button';

type TextButtonProps = Omit<ComponentProps<typeof Button>, 'variant'> & {
    variant: TextVariant;
};

export function TextButton({ className, variant, ...props }: TextButtonProps) {
    return (
        <Button
            variant={textButtonVariant[variant]}
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
