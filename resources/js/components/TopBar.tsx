import { Link } from '@tanstack/react-router';

import { Search } from 'lucide-react';

import { cn } from '../lib/utils';
import { PrimaryButton } from './PrimaryButton';
import { buttonVariants } from './ui/button';
import { Input } from './ui/input';

export function TopBar() {
    return (
        <nav className="flex w-full items-center justify-between bg-white px-6 py-3 shadow-md">
            <Link to="/" className="flex items-center gap-2 transition-colors hover:bg-gray-100">
                <img src="/images/logo.svg" alt="Logo" className="h-16 w-auto object-contain" />
            </Link>

            <div className="flex items-center gap-8">
                <div className="flex items-center gap-4">
                    <Link
                        to="/"
                        className={cn(
                            buttonVariants({ variant: 'ghost' }),
                            'h-auto border-none px-3 py-1.5 text-base font-bold transition-colors hover:bg-gray-100'
                        )}
                    >
                        Informasi
                    </Link>
                    <Link
                        to="/"
                        className={cn(
                            buttonVariants({ variant: 'ghost' }),
                            'h-auto border-none px-3 py-1.5 text-base font-bold transition-colors hover:bg-gray-100'
                        )}
                    >
                        Link Penting
                    </Link>
                    <Link
                        to="/"
                        className={cn(
                            buttonVariants({ variant: 'ghost' }),
                            'h-auto border-none px-3 py-1.5 text-base font-bold transition-colors hover:bg-gray-100'
                        )}
                    >
                        Masukan
                    </Link>
                </div>

                <div className="flex items-center gap-3">
                    <div className="relative flex w-48 items-center bg-gray-100 px-3 py-1.5">
                        <Input
                            type="text"
                            placeholder="Cari..."
                            className="h-auto w-full border-none bg-transparent p-0 pr-8 text-base focus-visible:ring-0 focus-visible:ring-offset-0 md:text-base"
                        />
                        <Search
                            className="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400"
                            size={16}
                        />
                    </div>
                    <PrimaryButton to="/">Admin</PrimaryButton>
                </div>
            </div>
        </nav>
    );
}
