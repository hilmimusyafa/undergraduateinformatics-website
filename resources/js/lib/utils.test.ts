import { describe, expect, it } from 'vitest';

import { cn } from './utils';

describe('cn', () => {
    it('joins class names', () => {
        expect(cn('a', 'b', 'c')).toBe('a b c');
    });

    it('merges conflicting tailwind classes with the last one winning', () => {
        expect(cn('px-2', 'px-4')).toBe('px-4');
        expect(cn('text-sm', 'text-lg')).toBe('text-lg');
    });

    it('filters falsy values', () => {
        expect(cn('a', false, null, undefined, 'b')).toBe('a b');
    });

    it('handles conditional objects', () => {
        expect(cn({ a: true, b: false })).toBe('a');
    });
});
