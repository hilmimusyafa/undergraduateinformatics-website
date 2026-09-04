import { render } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { RichTextContent } from './RichTextContent';

describe('RichTextContent', () => {
    it('renders plain text when no html is present', () => {
        const { container } = render(
            <RichTextContent content={{ text: 'Teks polos' }} as="span" />
        );

        expect(container).toHaveTextContent('Teks polos');
        expect(container.querySelector('[data-rich-text]')).not.toBeInTheDocument();
    });

    it('renders rich html when present', () => {
        const { container } = render(
            <RichTextContent content={{ text: 'Teks polos', html: '<b>tebal</b>' }} as="span" />
        );

        expect(container.querySelector('span[data-rich-text]')?.innerHTML).toBe('<b>tebal</b>');
    });

    it('renders nothing for empty content', () => {
        const { container } = render(<RichTextContent content={null} />);

        expect(container).toBeEmptyDOMElement();
    });
});
