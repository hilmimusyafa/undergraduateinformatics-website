import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { SearchBar } from './SearchBar';

describe('SearchBar', () => {
    it('renders a text input with a search placeholder', () => {
        render(<SearchBar />);

        expect(screen.getByPlaceholderText('Cari...')).toBeInTheDocument();
    });

    it('forwards props to the input', () => {
        render(<SearchBar defaultValue="program" />);

        expect(screen.getByPlaceholderText('Cari...')).toHaveValue('program');
    });

    it('applies a custom className to the wrapper', () => {
        const { container } = render(<SearchBar className="custom-wrapper" />);

        expect(container.querySelector('div')).toHaveClass('custom-wrapper');
    });
});
