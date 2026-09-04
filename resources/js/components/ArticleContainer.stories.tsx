import type { Story, StoryDefault } from '@ladle/react';

import { ArticleContainer } from './ArticleContainer';

export default {
    title: 'Article Container',
} satisfies StoryDefault;

export const Default: Story = () => (
    <ArticleContainer className="max-w-[37em]">
        <h1>Artikel</h1>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua.
        </p>
    </ArticleContainer>
);
