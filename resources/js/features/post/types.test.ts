import { describe, expect, it } from 'vitest';

import { isUpdated } from './types';

describe('isUpdated', () => {
    it('returns true when the timestamps differ', () => {
        expect(isUpdated('2026-09-01T00:00:00.000Z', '2026-09-05T00:00:00.000Z')).toBe(true);
    });

    it('returns false when the timestamps are equal', () => {
        expect(isUpdated('2026-09-01T00:00:00.000Z', '2026-09-01T00:00:00.000Z')).toBe(false);
    });
});
