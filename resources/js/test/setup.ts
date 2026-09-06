import '@testing-library/jest-dom/vitest';
import { vi } from 'vitest';

Object.defineProperty(window, 'matchMedia', {
    writable: true,
    value: vi.fn().mockImplementation((query) => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: vi.fn(),
        removeListener: vi.fn(),
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
        dispatchEvent: vi.fn(),
    })),
});

Object.defineProperty(window, 'scrollTo', {
    writable: true,
    value: vi.fn(),
});

Object.defineProperty(Element.prototype, 'scrollIntoView', {
    writable: true,
    value: vi.fn(),
});

class MockIntersectionObserver {
    observe() {}
    unobserve() {}
    disconnect() {}
}

Object.defineProperty(window, 'IntersectionObserver', {
    writable: true,
    configurable: true,
    value: MockIntersectionObserver,
});

class MockResizeObserver {
    observe() {}
    unobserve() {}
    disconnect() {}
}

Object.defineProperty(window, 'ResizeObserver', {
    writable: true,
    configurable: true,
    value: MockResizeObserver,
});

class MockPointerEvent extends MouseEvent {
    pointerId: number;
    pointerType: string;
    isPrimary: boolean;
    width: number;
    height: number;
    pressure: number;

    constructor(type: string, params: PointerEventInit = {}) {
        super(type, params);
        this.pointerId = params.pointerId ?? 1;
        this.pointerType = params.pointerType ?? 'mouse';
        this.isPrimary = params.isPrimary ?? true;
        this.width = params.width ?? 1;
        this.height = params.height ?? 1;
        this.pressure = params.pressure ?? 0.5;
    }
}

Object.defineProperty(window, 'PointerEvent', {
    writable: true,
    configurable: true,
    value: MockPointerEvent,
});
