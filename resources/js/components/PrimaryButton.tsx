import { type ComponentProps } from 'react';

import { Link } from '@tanstack/react-router';

import { type VariantProps } from 'class-variance-authority';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

export function PrimaryButton({
    className,
    ...props
}: ComponentProps<typeof Link> & VariantProps<typeof buttonVariants>) {
    return (
        <Link
            {...props}
            className={cn(
                buttonVariants(),
                'h-auto rounded-none border-none px-3 py-1.5 text-base font-bold',
                className
            )}
        />
    );
}
