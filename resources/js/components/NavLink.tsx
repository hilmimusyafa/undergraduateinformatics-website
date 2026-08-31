import { type ComponentProps } from 'react';

import { Link } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

type NavLinkProps = ComponentProps<typeof Link>;

export function NavLink({ className, ...props }: NavLinkProps) {
    return (
        <Link
            {...props}
            className={cn(
                buttonVariants({ variant: 'link' }),
                'text-muted-foreground hover:text-foreground data-[status=active]:text-foreground h-auto border-none px-3 py-1.5 text-base font-semibold',
                className
            )}
        />
    );
}
