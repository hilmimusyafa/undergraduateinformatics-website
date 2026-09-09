import { describe, expect, it } from 'vitest';

import { seoPage } from '@/lib/seo';

import { Route } from './posts.search';

type Search = { q?: string; page?: number };

function evaluateSearch(input: unknown): Search {
    const validator = Route.options.validateSearch as {
        ['~standard']?: { validate: (value: unknown) => { value: Search } };
        parse?: (value: unknown) => Search;
    };

    if (validator && '~standard' in validator) {
        return validator['~standard']!.validate(input).value;
    }
    if (validator && typeof validator.parse === 'function') {
        return validator.parse(input);
    }
    return {} as Search;
}

describe('posts search route', () => {
    it('sets the page title via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string }[];
        };

        const result = head({});
        expect(result.meta?.[0]?.title).toBe(seoPage('postSearch').title);
    });

    it('sets the page description via the head option', () => {
        const head = Route.options.head as unknown as (context: unknown) => {
            meta?: { title?: string; name?: string; content?: string }[];
        };

        const result = head({});
        const description = result.meta?.find((entry) => entry.name === 'description');
        expect(description?.content).toBe(seoPage('postSearch').description);
    });

    it('normalizes invalid page values to 1', () => {
        expect(evaluateSearch({ page: '0' })?.page).toBe(1);
        expect(evaluateSearch({ page: 'abc' })?.page).toBe(1);
    });

    it('keeps q and leaves page absent when not provided', () => {
        expect(evaluateSearch({ q: 'beasiswa' })?.q).toBe('beasiswa');
        expect(evaluateSearch({})?.page).toBeUndefined();
    });

    it('keeps the search page as its component', () => {
        expect(Route.options.component).toBeDefined();
    });
});
