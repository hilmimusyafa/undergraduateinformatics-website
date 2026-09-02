import { describe, expect, it } from 'vitest';

import { Route } from './feedback';

describe('feedback route', () => {
    it('sets the page title via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string }[];
        };

        expect(head).toBeDefined();

        const result = head({});
        expect(result.meta?.[0]?.title).toBe('Masukan - Portal Informasi Sarjana Informatika');
    });

    it('sets the page description via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe(
            'Berikan masukan dan evaluasi layanan untuk Program Studi Sarjana Informatika Telkom University melalui formulir.'
        );
    });

    it('keeps the feedback page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
