export interface ApiSuccessResponse<T> {
    status: 'success';
    data: T;
}

export interface ApiErrorResponse {
    status: 'error';
    message?: string;
    errors?: Record<string, string[]>;
}

export type ApiResponse<T> = ApiSuccessResponse<T> | ApiErrorResponse;
