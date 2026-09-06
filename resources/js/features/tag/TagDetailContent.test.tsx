import { describe, expect, it } from 'vitest';

import { type PostSummary } from '@/types/post';

import { groupPostsByMonth } from './groupPostsByMonth';

function post(id: number, updatedAt: string): PostSummary {
    return {
        id,
        slug: `post-${id}`,
        title: `Post ${id}`,
        subtitle: `Subtitle ${id}`,
        updated_at: updatedAt,
        tags: [],
    };
}

describe('groupPostsByMonth', () => {
    it('groups posts into a single month when dates share the same month', () => {
        const groups = groupPostsByMonth([
            post(1, '2026-04-05T12:00:00.000Z'),
            post(2, '2026-04-20T09:00:00.000Z'),
        ]);

        expect(groups).toHaveLength(1);
        expect(groups[0]).toEqual({
            key: '2026-04',
            label: 'April 2026',
            posts: [post(1, '2026-04-05T12:00:00.000Z'), post(2, '2026-04-20T09:00:00.000Z')],
        });
    });

    it('separates posts from different months into separate groups', () => {
        const groups = groupPostsByMonth([
            post(1, '2026-04-05T12:00:00.000Z'),
            post(2, '2026-09-01T12:00:00.000Z'),
        ]);

        expect(groups).toHaveLength(2);
    });

    it('sorts groups by month descending (newest first)', () => {
        const groups = groupPostsByMonth([
            post(1, '2024-01-10T12:00:00.000Z'),
            post(2, '2026-09-01T12:00:00.000Z'),
            post(3, '2025-06-15T12:00:00.000Z'),
        ]);

        expect(groups.map((group) => group.key)).toEqual(['2026-09', '2025-06', '2024-01']);
    });

    it('uses Indonesian month labels', () => {
        const groups = groupPostsByMonth([post(1, '2026-09-01T12:00:00.000Z')]);

        expect(groups[0].label).toBe('September 2026');
    });

    it('groups posts from the same month even across years', () => {
        const groups = groupPostsByMonth([
            post(1, '2025-04-10T12:00:00.000Z'),
            post(2, '2026-04-10T12:00:00.000Z'),
        ]);

        expect(groups).toHaveLength(2);
        expect(groups.map((group) => group.key)).toEqual(['2026-04', '2025-04']);
        expect(groups.map((group) => group.label)).toEqual(['April 2026', 'April 2025']);
    });

    it('returns an empty array for no posts', () => {
        expect(groupPostsByMonth([])).toEqual([]);
    });
});
