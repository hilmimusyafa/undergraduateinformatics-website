import { describe, expect, it } from 'vitest';

import { seoDefaults, seoPage } from '@/lib/seo';

import { Route } from './tags.$slug';

describe('tags detail route', () => {
    it('uses the tag name and description from loader data for the head title', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string }[];
        };

        const result = head({
            loaderData: { data: { name: 'Kurikulum', description: 'Info kurikulum' } },
        });
        expect(result.meta?.[0]?.title).toBe(`Kurikulum - ${seoDefaults.title}`);
    });

    it('sets the page description from loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({
            loaderData: { data: { name: 'Kurikulum', description: 'Info kurikulum' } },
        });
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe('Info kurikulum');
    });

    it('falls back to the static tagDetail config without loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('tagDetail').title);
        expect(result.meta?.find((entry) => entry.name === 'description')?.content).toBe(
            seoPage('tagDetail').description
        );
    });

    it('keeps the tag detail page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
