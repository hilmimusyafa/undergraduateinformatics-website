import { render } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { RichText } from './RichText';

describe('RichText', () => {
    it('renders the sanitized html', () => {
        const { container } = render(<RichText html="Halo <b>dunia</b>" />);

        expect(container.querySelector('[data-rich-text]')?.innerHTML).toBe('Halo <b>dunia</b>');
    });

    it('renders nothing for empty html', () => {
        const { container } = render(<RichText html={null} />);

        expect(container).toBeEmptyDOMElement();
    });

    it('honours the as prop', () => {
        const { container } = render(<RichText html="teks" as="span" />);

        expect(container.querySelector('span[data-rich-text]')).toBeInTheDocument();
    });
});
