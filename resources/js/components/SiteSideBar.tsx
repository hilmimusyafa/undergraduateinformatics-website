import { NavButton } from './NavButton';
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
                    <div className="mx-3 my-px">
                        <NavButton to="/" onClick={onClose}>
                            Beranda
                        </NavButton>
                    </div>
                    <div className="mx-3 my-px">
                        <NavButton to="/explore" onClick={onClose}>
                            Informasi
                        </NavButton>
                    </div>
                    <div className="mx-3 my-px">
                        <NavButton to="/links" onClick={onClose}>
                            Tautan
                        </NavButton>
                    </div>
                    <div className="mx-3 my-px">
                        <NavButton to="/feedback" onClick={onClose}>
                            Masukan
                        </NavButton>
                    </div>
                    <div className="mx-3 my-px">
                        <NavButton to="/reservation" onClick={onClose}>
                            Pertemuan
                        </NavButton>
                    </div>
                    <hr className="border-border mx-6 my-2 border-t" />
                    <div className="mx-3 my-px">
                        <NavButton to="/reservation" onClick={onClose}>
                            Masuk
                        </NavButton>
                    </div>
                </div>
            </div>
        </aside>
    );
}
