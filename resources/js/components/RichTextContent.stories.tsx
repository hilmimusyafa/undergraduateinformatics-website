import type { Story, StoryDefault } from '@ladle/react';

import { RichTextContent } from './RichTextContent';

export default {
    title: 'Rich Text Content',
} satisfies StoryDefault;

export const Plain: Story = () => <RichTextContent content={{ text: 'Teks polos' }} as="span" />;

export const Rich: Story = () => (
    <RichTextContent content={{ text: 'Teks polos', html: 'Teks <b>kaya</b>' }} as="span" />
);

export const Empty: Story = () => <RichTextContent content={null} as="span" />;
