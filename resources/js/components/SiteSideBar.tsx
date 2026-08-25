import { NavigationLink } from './NavigationLink';
import { SearchBar } from './SearchBar';

interface SiteSideBarProps {
    isOpen: boolean;
    onClose: () => void;
}

export function SiteSideBar({ isOpen, onClose }: SiteSideBarProps) {
    return (
        <aside
            className={`bg-background fixed top-18 bottom-0 left-0 z-50 mr-16 w-full max-w-xs scrollbar-none overflow-y-auto transition-transform duration-300 ease-in-out lg:hidden ${
                isOpen ? 'translate-x-0' : '-translate-x-full'
            }`}
        >
            <div className="flex flex-col gap-4">
                <div className="px-6 pt-6">
                    <SearchBar className="w-full px-4 py-2" />
                </div>

                <div className="flex flex-col">
                    <div className="px-3 py-0.5">
                        <NavigationLink
                            to="/"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Beranda
                        </NavigationLink>
                    </div>
                    <div className="px-3 py-px">
                        <NavigationLink
                            to="/explore"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Informasi
                        </NavigationLink>
                    </div>
                    <div className="px-3 py-px">
                        <NavigationLink
                            to="/links"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Tautan
                        </NavigationLink>
                    </div>
                    <div className="px-3 py-px">
                        <NavigationLink
                            to="/feedback"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Masukan
                        </NavigationLink>
                    </div>
                    <div className="px-3 py-px">
                        <NavigationLink
                            to="/reservation"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Pertemuan
                        </NavigationLink>
                    </div>
                    <hr className="mx-6 my-2 border-t border-gray-300" />
                    <div className="px-3 py-px">
                        <NavigationLink
                            to="/reservation"
                            onClick={onClose}
                            className="active:bg-muted w-full justify-start py-3.5 pr-0 pl-3"
                            activeClassName="bg-muted"
                        >
                            Masuk
                        </NavigationLink>
                    </div>
                </div>
            </div>
        </aside>
    );
}
