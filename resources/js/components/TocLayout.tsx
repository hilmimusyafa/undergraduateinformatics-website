import { type ReactNode } from 'react';

import { MainAsideLayout } from '@/components/MainAsideLayout';
import { TableOfContents, type TocItem } from '@/components/TableOfContents';

interface TocLayoutProps {
    title: string;
    description?: string;
    items: TocItem[];
    emptyMessage: string;
    children: ReactNode;
}

export function TocLayout({ title, description, items, emptyMessage, children }: TocLayoutProps) {
    const scrollTo = (id: string) => {
        document.getElementById(id)?.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    };

    return (
        <MainAsideLayout
            mainContent={
                <>
                    <h1>{title}</h1>
                    {description && <p className="text-muted-foreground">{description}</p>}
                    {items.length > 0 && (
                        <div className="mt-10 md:mt-9 lg:hidden">
                            <TableOfContents items={items} onSelect={scrollTo} />
                        </div>
                    )}
                    {items.length === 0 ? (
                        <p
                            role="status"
                            className="text-muted-foreground mt-[calc(var(--typeset-flow)*1.4)]"
                        >
                            {emptyMessage}
                        </p>
                    ) : (
                        children
                    )}
                </>
            }
            asideContent={<TableOfContents items={items} onSelect={scrollTo} />}
        />
    );
}
