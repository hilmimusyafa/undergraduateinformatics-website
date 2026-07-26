import { type ComponentProps } from 'react';

import { Link } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

export function TopBarLink({ className, ...props }: ComponentProps<typeof Link>) {
    return (
        <Link
            {...props}
            className={cn(
                buttonVariants({ variant: 'ghost' }),
                'h-auto border-none px-3 py-1.5 text-base font-bold transition-colors hover:bg-gray-100',
                className
            )}
        />
    );
}
