import { type ApiSuccessResponse } from '@/types/api';

export interface Link {
    id: number;
    name: string;
    link: string;
    updated_at: string;
}

export interface LinkSection {
    id: number;
    name: string;
    order_number: number;
    links: Link[];
}

export type LinksPayload = ApiSuccessResponse<LinkSection[]>;
