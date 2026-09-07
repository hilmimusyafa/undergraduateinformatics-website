import { type ComponentProps, useState } from 'react';

import { Search } from 'lucide-react';

import { cn } from '../lib/utils';
import { Input } from './ui/input';

interface SearchBarProps extends Omit<ComponentProps<typeof Input>, 'onSubmit'> {
    onSubmit?: (value: string) => void;
    variant?: 'filled' | 'underline';
}

export function SearchBar({
    className,
    onSubmit,
    onKeyDown,
    onBlur,
    variant = 'filled',
    ...props
}: SearchBarProps) {
    const [isPointerSession, setIsPointerSession] = useState(false);

    return (
        <div
            onPointerDown={() => setIsPointerSession(true)}
            className={cn(
                'has-focus-visible:global-ring relative flex w-48 items-center',
                isPointerSession && 'no-ring',
                variant === 'filled' ? 'bg-gray-100 px-3 py-1.5' : 'border-b px-0 py-1.5',
                className
            )}
        >
            <Input
                type="text"
                placeholder="Cari..."
                {...props}
                onBlur={(event) => {
                    setIsPointerSession(false);
                    onBlur?.(event);
                }}
                onKeyDown={(event) => {
                    onKeyDown?.(event);

                    if (event.key === 'Enter') {
                        event.preventDefault();
                        onSubmit?.(event.currentTarget.value);
                    }
                }}
                className="no-ring h-auto w-full border-none bg-transparent p-0 pr-8 text-lg md:text-base"
            />
            <Search className="absolute top-1/2 right-3 size-4 -translate-y-1/2 text-gray-400" />
        </div>
    );
}
