import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, expect, it, vi } from 'vitest';

import { TableOfContents } from './TableOfContents';
import { type LinkSection } from './types';

const sections: LinkSection[] = [
    { id: 1, name: 'Akademik', order_number: 1, links: [] },
    { id: 2, name: 'MBKM', order_number: 2, links: [] },
    { id: 6, name: 'Alumni', order_number: 3, links: [] },
];

describe('TableOfContents', () => {
    it('renders a Daftar Isi navigation with an unordered list', () => {
        render(<TableOfContents sections={sections} onSelect={() => undefined} />);

        const navigation = screen.getByRole('navigation', { name: 'Daftar Isi' });
        expect(navigation.querySelector('ul')).toBeInTheDocument();
    });

    it('renders one button per section labelled with the section name', () => {
        render(<TableOfContents sections={sections} onSelect={() => undefined} />);

        expect(screen.getByRole('button', { name: 'Akademik' })).toBeInTheDocument();
        expect(screen.getByRole('button', { name: 'MBKM' })).toBeInTheDocument();
        expect(screen.getByRole('button', { name: 'Alumni' })).toBeInTheDocument();
    });

    it('calls onSelect with the section id when a button is clicked', async () => {
        const user = userEvent.setup();
        const onSelect = vi.fn();

        render(<TableOfContents sections={sections} onSelect={onSelect} />);

        await user.click(screen.getByRole('button', { name: 'MBKM' }));

        expect(onSelect).toHaveBeenCalledTimes(1);
        expect(onSelect).toHaveBeenCalledWith(2);
    });
});
