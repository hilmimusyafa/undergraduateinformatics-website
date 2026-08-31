import { Link } from '@tanstack/react-router';

import { IconListSearch } from '@tabler/icons-react';
import { X } from 'lucide-react';

import { NavLink } from './NavLink';
import { PrimaryButton } from './PrimaryButton';
import { SearchBar } from './SearchBar';
import { Button } from './ui/button';

interface TopBarProps {
    isSidebarOpen: boolean;
    onToggleSidebar: () => void;
}

export function TopBar({ isSidebarOpen, onToggleSidebar }: TopBarProps) {
    return (
        <nav
            className={`sticky top-0 z-50 h-full max-h-18 w-full bg-white transition-shadow duration-300 ${isSidebarOpen ? 'shadow-none' : 'shadow-md'}`}
        >
            <div className="mx-auto flex h-full w-full max-w-7xl items-center justify-between px-4 py-3">
                <Link to="/">
                    <img
                        src="/images/logo.png"
                        alt="Logo"
                        className="h-full max-h-12 object-contain"
                    />
                </Link>

                <div className="hidden items-center gap-6 lg:flex">
                    <div className="flex items-center gap-3">
                        <NavLink to="/">Beranda</NavLink>
                        <NavLink to="/explore">Informasi</NavLink>
                        <NavLink to="/link">Tautan</NavLink>
                        <NavLink to="/feedback">Masukan</NavLink>
                        <NavLink to="/reservation">Pertemuan</NavLink>
                        <SearchBar />
                        <PrimaryButton
                            nativeButton={false}
                            render={<Link to="/" />}
                            className="font-semibold"
                        >
                            Masuk
                        </PrimaryButton>
                    </div>
                </div>

                <Button
                    variant="ghost"
                    size="icon"
                    className="-mr-2 size-10 text-gray-600 lg:hidden"
                    onClick={onToggleSidebar}
                    aria-label="Toggle navigation menu"
                >
                    {isSidebarOpen ? (
                        <X className="size-7" strokeWidth={1.5} />
                    ) : (
                        <IconListSearch className="size-7" strokeWidth={1.5} />
                    )}
                </Button>
            </div>
        </nav>
    );
}
