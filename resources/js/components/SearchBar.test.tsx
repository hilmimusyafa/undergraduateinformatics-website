import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, expect, it, vi } from 'vitest';

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

    it('renders an underline variant without a background', () => {
        const { container } = render(<SearchBar variant="underline" />);

        const wrapper = container.querySelector('div');
        expect(wrapper).not.toHaveClass('bg-gray-100');
        expect(wrapper).toHaveClass('border-b');
    });

    it('renders the filled variant by default', () => {
        const { container } = render(<SearchBar />);

        expect(container.querySelector('div')).toHaveClass('bg-gray-100');
    });

    it('calls onSubmit with the current value when Enter is pressed', async () => {
        const user = userEvent.setup();
        const onSubmit = vi.fn();

        render(<SearchBar onSubmit={onSubmit} />);

        await user.type(screen.getByPlaceholderText('Cari...'), 'program{enter}');

        expect(onSubmit).toHaveBeenCalledWith('program');
    });

    it('suppresses the focus ring for pointer interaction and restores it on blur', async () => {
        const user = userEvent.setup();
        const { container } = render(<SearchBar />);
        const wrapper = container.querySelector('div')!;

        expect(wrapper).not.toHaveClass('no-ring');

        await user.click(screen.getByPlaceholderText('Cari...'));

        expect(wrapper).toHaveClass('no-ring');

        await user.tab();

        expect(wrapper).not.toHaveClass('no-ring');
    });
});
