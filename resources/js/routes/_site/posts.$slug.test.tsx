import { describe, expect, it } from 'vitest';

import { seoDefaults, seoPage } from '@/lib/seo';

import { Route } from './posts.$slug';

describe('posts detail route', () => {
    it('uses the post title and subtitle from loader data for the head', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({
            loaderData: {
                data: { title: 'Registrasi Ganjil 2026', subtitle: 'Info registrasi' },
            },
        });
        expect(result.meta?.[0]?.title).toBe(`Registrasi Ganjil 2026 - ${seoDefaults.title}`);
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe('Info registrasi');
    });

    it('falls back to the static postDetail config without loader data', () => {
        const head = Route.options.head as unknown as (context: { loaderData?: unknown }) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('postDetail').title);
        expect(result.meta?.find((entry) => entry.name === 'description')?.content).toBe(
            seoPage('postDetail').description
        );
    });

    it('keeps the post detail page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
