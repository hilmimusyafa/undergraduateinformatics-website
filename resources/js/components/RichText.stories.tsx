import type { Story, StoryDefault } from '@ladle/react';

import { RichText } from './RichText';

export default {
    title: 'Rich Text',
} satisfies StoryDefault;

export const Plain: Story = () => <RichText as="span" html="Teks polos tanpa format" />;

export const Formatted: Story = () => (
    <RichText
        as="span"
        html='Halo <b>tebal</b>, <i>miring</i>, <u>garis</u> dan <a href="https://example.com">link</a>'
    />
);

export const List: Story = () => (
    <RichText as="div" html="<ul><li>item satu</li><li>item dua</li></ul>" />
);
