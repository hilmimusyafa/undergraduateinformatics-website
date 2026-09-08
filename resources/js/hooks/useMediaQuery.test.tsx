import { act, renderHook } from '@testing-library/react';
import { afterEach, describe, expect, it, vi } from 'vitest';

import { useMediaQuery } from './useMediaQuery';

describe('useMediaQuery', () => {
    const listeners = new Map<string, (event: MediaQueryListEvent) => void>();

    function setMatchMedia(initialMatches: boolean) {
        const mql = {
            matches: initialMatches,
            media: '(min-width: 480px)',
            addEventListener: (eventName: string, cb: (event: MediaQueryListEvent) => void) => {
                void eventName;
                listeners.set('change', cb);
            },
            removeEventListener: (eventName: string) => {
                void eventName;
                listeners.delete('change');
            },
        };

        vi.stubGlobal('matchMedia', vi.fn().mockReturnValue(mql));
    }

    afterEach(() => {
        listeners.clear();
        vi.unstubAllGlobals();
    });

    it('returns the initial match state', () => {
        setMatchMedia(true);
        const { result } = renderHook(() => useMediaQuery('(min-width: 480px)'));
        expect(result.current).toBe(true);
    });

    it('updates when the media query state changes', () => {
        setMatchMedia(false);
        const { result } = renderHook(() => useMediaQuery('(min-width: 480px)'));

        expect(result.current).toBe(false);

        act(() => listeners.get('change')?.({ matches: true } as MediaQueryListEvent));

        expect(result.current).toBe(true);
    });
});
