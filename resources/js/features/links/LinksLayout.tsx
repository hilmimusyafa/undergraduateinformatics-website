import { type ComponentProps, type ReactNode } from 'react';

import { ArticleContainer } from '@/components/ArticleContainer';
import { cn } from '@/lib/utils';

interface LinksLayoutProps extends ComponentProps<'div'> {
    mainContent: ReactNode;
    asideContent: ReactNode;
    asideClassName?: string;
}

export function LinksLayout({
    mainContent,
    asideContent,
    asideClassName,
    ...wrapperProps
}: LinksLayoutProps) {
    return (
        <div
            className="lg:mx-auto lg:flex lg:w-full lg:max-w-4xl lg:justify-between"
            {...wrapperProps}
        >
            <ArticleContainer className="max-w-[37em] lg:mx-0">{mainContent}</ArticleContainer>
            <aside className="hidden lg:block">
                <div
                    className={cn(
                        'mt-10 max-w-3xs scrollbar-none md:mt-9 lg:sticky lg:top-27 lg:max-h-[calc(100vh-10rem)] lg:overflow-y-auto',
                        asideClassName
                    )}
                >
                    {asideContent}
                </div>
            </aside>
        </div>
    );
}
