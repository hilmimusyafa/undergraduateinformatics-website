import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';

import { ArticleContainer } from './ArticleContainer';

describe('ArticleContainer', () => {
    it('renders its children', () => {
        render(
            <ArticleContainer>
                <h1>Artikel</h1>
            </ArticleContainer>
        );

        expect(screen.getByRole('heading', { name: 'Artikel' })).toBeInTheDocument();
    });

    it('merges extra class names', () => {
        render(
            <ArticleContainer className="mt-4">
                <p>Konten</p>
            </ArticleContainer>
        );

        expect(screen.getByText('Konten').parentElement).toHaveClass(
            'typeset',
            'typeset-article',
            'mt-4'
        );
    });

    it('does not constrain the width by default', () => {
        render(
            <ArticleContainer>
                <p>Konten</p>
            </ArticleContainer>
        );

        expect(screen.getByText('Konten').parentElement).not.toHaveClass('max-w-[37em]');
    });

    it('applies an explicit max-width passed via className', () => {
        render(
            <ArticleContainer className="max-w-[37em]">
                <p>Konten</p>
            </ArticleContainer>
        );

        expect(screen.getByText('Konten').parentElement).toHaveClass('max-w-[37em]');
    });
});
