import { TextLink } from '@/components/TextLink';

import { LinksLayout } from './LinksLayout';
import { TableOfContents } from './TableOfContents';
import { sectionId } from './sectionId';
import { type LinkSection } from './types';

export function LinksContent({ sections }: { sections: LinkSection[] }) {
    const scrollToSection = (sectionIdNumber: number) => {
        document.getElementById(sectionId(sectionIdNumber))?.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    };

    return (
        <LinksLayout
            mainContent={
                <>
                    <h1>Tautan Penting</h1>
                    <p className="text-muted-foreground">
                        Kumpulan tautan penting terkait informasi di Program Studi Sarjana
                        Informatika Telkom University.
                    </p>
                    <div className="mt-10 md:mt-9 lg:hidden">
                        <TableOfContents sections={sections} onSelect={scrollToSection} />
                    </div>
                    {sections.map((section) => (
                        <section key={section.id}>
                            <h2 id={sectionId(section.id)} className="scroll-mt-28 md:scroll-mt-27">
                                {section.name}
                            </h2>
                            {section.links.length === 0 ? (
                                <p className="text-muted-foreground">
                                    Belum ada tautan pada section ini.
                                </p>
                            ) : (
                                <ul>
                                    {section.links.map((link) => (
                                        <li key={link.id}>
                                            <TextLink
                                                variant="underline"
                                                className="whitespace-normal no-underline"
                                                to={link.link}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                {link.name}
                                            </TextLink>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </section>
                    ))}
                </>
            }
            asideContent={<TableOfContents sections={sections} onSelect={scrollToSection} />}
        />
    );
}
