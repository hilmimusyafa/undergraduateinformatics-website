import { describe, expect, it } from 'vitest';

import { seoPage } from '@/lib/seo';

import { Route } from './tags';

describe('tags route', () => {
    it('sets the page title via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string }[];
        };

        expect(head).toBeDefined();

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('tagList').title);
    });

    it('sets the page description via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe(seoPage('tagList').description);
    });

    it('keeps the tags index page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
