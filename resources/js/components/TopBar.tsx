import { Link } from '@tanstack/react-router';

import { Search } from 'lucide-react';

import { PrimaryButton } from './PrimaryButton';
import { TopBarLink } from './TopBarLink';
import { Input } from './ui/input';

export function TopBar() {
    return (
        <nav className="flex w-full items-center justify-between bg-white px-3 py-1.5 shadow-md md:px-3 md:py-1.5 lg:px-4 lg:py-2">
            <Link to="/">
                <img
                    src="/images/icon.png"
                    alt="Logo"
                    className="block h-10 w-auto object-contain py-1.5 sm:hidden"
                />

                <img
                    src="/images/logo.png"
                    alt="Logo"
                    className="hidden h-10 w-auto object-contain sm:block md:h-12 lg:h-14"
                />
            </Link>

            <div className="flex items-center gap-1 md:gap-3 lg:gap-6">
                <div className="flex items-center gap-1 lg:gap-3">
                    <TopBarLink to="/">Informasi</TopBarLink>
                    <TopBarLink to="/">Tautan</TopBarLink>
                    <TopBarLink to="/">Masukan</TopBarLink>
                    <TopBarLink to="/">Pertemuan</TopBarLink>
                </div>

                <div className="flex items-center gap-1 md:gap-3">
                    <div className="relative flex w-18 items-center bg-gray-100 px-2 py-1 text-sm sm:w-24 md:w-36 lg:w-48 lg:px-3 lg:py-1.5 lg:text-base">
                        <Input
                            type="text"
                            placeholder="Cari..."
                            className="h-auto w-full border-none bg-transparent p-0 pr-8 text-xs md:text-sm lg:text-base"
                        />
                        <Search className="absolute top-1/2 right-3 size-3 -translate-y-1/2 text-gray-400 lg:size-4" />
                    </div>
                    <PrimaryButton to="/">Admin</PrimaryButton>
                </div>
            </div>
        </nav>
    );
}
