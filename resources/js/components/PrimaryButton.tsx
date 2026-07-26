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
                'h-auto border-none px-1 py-1 text-xs font-bold sm:px-2 md:text-sm lg:px-3 lg:py-1.5 lg:text-base',
                className
            )}
        />
    );
}
