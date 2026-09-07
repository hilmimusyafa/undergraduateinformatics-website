import { AxiosError, type AxiosResponse } from 'axios';
import { vi } from 'vitest';

export async function routerModuleMock(overrides: Record<string, unknown> = {}) {
    const actual =
        await vi.importActual<typeof import('@tanstack/react-router')>('@tanstack/react-router');

    return {
        ...actual,
        createLink: (Comp: any) =>
            function MockedLink({ to, params, ...props }: any) {
                const href =
                    typeof to === 'string' && params
                        ? to.replace(/\$[^/]+/g, (key: string) => params[key.slice(1)] ?? key)
                        : to;

                return <Comp href={href} {...props} />;
            },
        ...overrides,
    };
}

export function axiosError(status: number, message?: string) {
    const error = new AxiosError(message ?? 'Request failed');
    error.response = {
        status,
        data: message ? { message } : {},
    } as AxiosResponse;
    return error;
}
