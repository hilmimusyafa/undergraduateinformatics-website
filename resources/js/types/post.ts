import { type Tag } from '@/types/tag';

export interface Post {
    id: number;
    slug: string;
    title: string;
    subtitle: string;
    body: string;
    image: string | null;
    updated_at: string;
    tags: Tag[];
}

export type PostSummary = Omit<Post, 'body' | 'image'>;
