import { type ApiSuccessResponse } from '@/types/api';

export interface Tag {
    id: number;
    slug: string;
    name: string;
    description?: string | null;
    posts_count: number;
}

export type TagsPayload = ApiSuccessResponse<Tag[]>;
