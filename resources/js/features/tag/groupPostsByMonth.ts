import { format } from 'date-fns';
import { id } from 'date-fns/locale';

import { type PostSummary } from '@/types/post';

export interface PostGroup {
    key: string;
    label: string;
    posts: PostSummary[];
}

export function groupPostsByMonth(posts: PostSummary[]): PostGroup[] {
    const groups = new Map<string, PostGroup>();

    for (const post of posts) {
        const date = new Date(post.updated_at);
        const key = format(date, 'yyyy-MM');
        const label = format(date, 'MMMM yyyy', { locale: id });

        const group = groups.get(key);
        if (group) {
            group.posts.push(post);
        } else {
            groups.set(key, { key, label, posts: [post] });
        }
    }

    return [...groups.values()].sort((a, b) => b.key.localeCompare(a.key));
}
