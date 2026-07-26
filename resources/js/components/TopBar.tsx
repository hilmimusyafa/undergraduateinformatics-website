import { Link } from '@tanstack/react-router';

import { Search } from 'lucide-react';

import { PrimaryButton } from './PrimaryButton';
import { TopBarLink } from './TopBarLink';
import { Input } from './ui/input';

export function TopBar() {
    return (
        <nav className="flex w-full items-center justify-between bg-white px-6 py-3 shadow-md">
            <Link to="/" className="flex items-center gap-2">
                <img src="/images/logo.svg" alt="Logo" className="h-16 w-auto object-contain" />
            </Link>

            <div className="flex items-center gap-8">
                <div className="flex items-center gap-4">
                    <TopBarLink to="/">Informasi</TopBarLink>
                    <TopBarLink to="/">Link Penting</TopBarLink>
                    <TopBarLink to="/">Masukan</TopBarLink>
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
