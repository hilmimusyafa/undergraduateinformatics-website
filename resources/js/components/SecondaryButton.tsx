import { type ComponentProps } from 'react';

import { cn } from '../lib/utils';
import { Button } from './ui/button';

type SecondaryButtonProps = ComponentProps<typeof Button>;

export function SecondaryButton({ className, ...props }: SecondaryButtonProps) {
    return (
        <Button
            variant="outline"
            className={cn('h-auto px-3 py-1.5 text-base', className)}
            {...props}
        />
    );
}
