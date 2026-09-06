import { type ComponentProps, type ReactNode } from 'react';

import { ArticleContainer } from '@/components/ArticleContainer';
import { cn } from '@/lib/utils';

interface MainAsideLayoutProps extends ComponentProps<'div'> {
    mainContent: ReactNode;
    asideContent: ReactNode;
    asideClassName?: string;
}

export function MainAsideLayout({
    mainContent,
    asideContent,
    asideClassName,
    ...wrapperProps
}: MainAsideLayoutProps) {
    return (
        <div
            className="lg:mx-auto lg:flex lg:w-full lg:max-w-4xl lg:justify-between"
            {...wrapperProps}
        >
            <ArticleContainer className="max-w-[37em] lg:mx-0">{mainContent}</ArticleContainer>
            <aside className="hidden w-full lg:block lg:max-w-3xs">
                <div className={cn('mt-10 md:mt-9 lg:h-full', asideClassName)}>{asideContent}</div>
            </aside>
        </div>
    );
}
