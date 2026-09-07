import { type ApiSuccessResponse } from '@/types/api';
import { type Post } from '@/types/post';

export type PostPayload = ApiSuccessResponse<Post>;

export function isUpdated(createdAt: string, updatedAt: string): boolean {
    return createdAt !== updatedAt;
}
