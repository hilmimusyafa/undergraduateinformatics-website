import { AxiosError, type AxiosResponse } from 'axios';
import { describe, expect, it } from 'vitest';

import { isNotFoundError } from './errors';

describe('isNotFoundError', () => {
    it('returns true for an AxiosError with a 404 status', () => {
        const error = new AxiosError('Request failed');
        error.response = { status: 404 } as AxiosResponse;
        expect(isNotFoundError(error)).toBe(true);
    });

    it('returns false for other error statuses', () => {
        const error = new AxiosError('Request failed');
        error.response = { status: 500 } as AxiosResponse;
        expect(isNotFoundError(error)).toBe(false);
    });

    it('returns false for non-Axios errors', () => {
        expect(isNotFoundError(new Error('boom'))).toBe(false);
    });
});
