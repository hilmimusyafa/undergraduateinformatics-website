import { type ComponentProps } from 'react';

import { Search } from 'lucide-react';

import { cn } from '../lib/utils';
import { Input } from './ui/input';

export function SearchBar({ className, ...props }: ComponentProps<typeof Input>) {
    return (
        <div
            className={cn(
                'has-focus-visible:global-ring relative flex w-48 items-center bg-gray-100 px-3 py-1.5',
                className
            )}
        >
            <Input
                type="text"
                placeholder="Cari..."
                {...props}
                className="no-ring h-auto w-full border-none bg-transparent p-0 pr-8 text-base md:text-base"
            />
            <Search className="absolute top-1/2 right-3 size-4 -translate-y-1/2 text-gray-400" />
        </div>
    );
}
