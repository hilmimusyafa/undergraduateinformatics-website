import { describe, expect, it } from 'vitest';

import { LinksPage } from '@/features/links/LinksPage';
import { seoPage } from '@/lib/seo';

import { Route } from './links';

describe('links route', () => {
    it('sets the page title via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string }[];
        };

        expect(head).toBeDefined();

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('links').title);
    });

    it('sets the page description via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe(seoPage('links').description);
    });

    it('renders the links index page as its component', () => {
        expect(Route.options.component).toBe(LinksPage);
    });
});
