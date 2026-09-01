import { type ComponentProps } from 'react';

import { Link } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { buttonVariants } from './ui/button';

type NavItemProps = ComponentProps<typeof Link> & {
    variant: 'top' | 'side';
};

export function NavItem({ variant, className, ...linkProps }: NavItemProps) {
    const variantClass =
        variant === 'side'
            ? buttonVariants({ variant: 'ghost' })
            : buttonVariants({ variant: 'link' });

    const layoutClass =
        variant === 'side'
            ? 'text-muted-foreground data-[status=active]:text-foreground active:bg-muted data-[status=active]:bg-muted h-auto w-full justify-start py-3.5 pr-0 pl-3 text-base font-semibold'
            : 'text-muted-foreground hover:text-foreground data-[status=active]:text-foreground h-auto border-none px-3 py-1.5 text-base font-semibold';

    return <Link {...linkProps} className={cn(variantClass, layoutClass, className)} />;
}
