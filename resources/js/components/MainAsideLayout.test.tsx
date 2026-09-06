import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { MainAsideLayout } from './MainAsideLayout';

describe('MainAsideLayout', () => {
    it('renders main and aside content', () => {
        render(<MainAsideLayout mainContent={<p>Main</p>} asideContent={<p>Aside</p>} />);

        expect(screen.getByText('Main')).toBeInTheDocument();
        expect(screen.getByText('Aside')).toBeInTheDocument();
    });

    it('keeps the aside full width up to the 3xs cap on desktop', () => {
        render(<MainAsideLayout mainContent={<p>Main</p>} asideContent={<p>Aside</p>} />);

        const aside = screen.getByText('Aside').closest('aside');
        expect(aside).toHaveClass('w-full', 'lg:max-w-3xs');
    });
});
