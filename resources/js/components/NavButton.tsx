import { type ComponentProps, type ReactNode } from 'react';

import { Link } from '@tanstack/react-router';

import { cn } from '../lib/utils';
import { Button } from './ui/button';

type NavButtonProps = Omit<ComponentProps<typeof Link>, 'children'> & {
    children: ReactNode;
};

export function NavButton({ className, children, ...linkProps }: NavButtonProps) {
    return (
        <Button
            variant="ghost"
            nativeButton={false}
            render={<Link {...linkProps} />}
            className={cn(
                'text-muted-foreground data-[status=active]:text-foreground active:bg-muted data-[status=active]:bg-muted h-auto w-full justify-start py-3.5 pr-0 pl-3 text-base font-semibold',
                className
            )}
        >
            {children}
        </Button>
    );
}
