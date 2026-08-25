import { type ComponentProps } from 'react';

import { Link, useLinkProps } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

interface NavigationLinkProps extends ComponentProps<typeof Link> {
    activeClassName?: string;
}

export function NavigationLink({ className, activeClassName, ...props }: NavigationLinkProps) {
    const linkProps = useLinkProps(props as any) as { 'data-status'?: string };
    const isActive = linkProps['data-status'] === 'active';

    return (
        <Link
            {...props}
            className={cn(
                buttonVariants({ variant: 'link' }),
                'h-auto border-none px-3 py-1.5 text-base font-bold hover:text-gray-800',
                isActive ? `text-foreground ${activeClassName ?? ''}` : 'text-gray-600',
                className
            )}
        />
    );
}
