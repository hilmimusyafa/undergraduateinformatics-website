import { TextButton } from '@/components/TextButton';

import { type LinkSection } from './types';

interface TableOfContentsProps {
    sections: LinkSection[];
    onSelect: (sectionId: number) => void;
}

export function TableOfContents({ sections, onSelect }: TableOfContentsProps) {
    return (
        <div className="typeset typeset-article">
            <h3>Daftar Isi</h3>
            <nav aria-label="Daftar Isi">
                <ul className="border-border border-l pl-2">
                    {sections.map((section) => (
                        <li key={section.id} className="list-none">
                            <TextButton
                                variant="fade"
                                className="text-muted-foreground hover:text-foreground text-left whitespace-normal lg:text-sm"
                                onClick={() => onSelect(section.id)}
                            >
                                {section.name}
                            </TextButton>
                        </li>
                    ))}
                </ul>
            </nav>
        </div>
    );
}
