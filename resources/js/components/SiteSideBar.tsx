import { NavItem } from './NavItem';
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
                <div className="mx-6 mt-6">
                    <SearchBar className="w-full px-4 py-2" />
                </div>

                <div className="flex flex-col">
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/" onClick={onClose}>
                            Beranda
                        </NavItem>
                    </div>
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/tags" onClick={onClose}>
                            Informasi
                        </NavItem>
                    </div>
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/links" onClick={onClose}>
                            Tautan
                        </NavItem>
                    </div>
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/feedback" onClick={onClose}>
                            Masukan
                        </NavItem>
                    </div>
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/reservation" onClick={onClose}>
                            Pertemuan
                        </NavItem>
                    </div>
                    <hr className="border-border mx-6 my-2 border-t" />
                    <div className="mx-3 my-0.5">
                        <NavItem variant="side" to="/reservation" onClick={onClose}>
                            Masuk
                        </NavItem>
                    </div>
                </div>
            </div>
        </aside>
    );
}
