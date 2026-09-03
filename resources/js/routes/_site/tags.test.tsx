import { describe, expect, it } from 'vitest';

import { Route } from './tags';

describe('tags route', () => {
    it('sets the page title via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string }[];
        };

        expect(head).toBeDefined();

        const result = head({});
        expect(result.meta?.[0]?.title).toBe('Daftar Label - Portal Informasi Sarjana Informatika');
    });

    it('sets the page description via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe(
            'Jelajahi informasi Program Studi Sarjana Informatika Telkom University berdasarkan label.'
        );
    });

    it('keeps the tags index page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
