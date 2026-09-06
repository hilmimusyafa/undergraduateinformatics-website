import { type MsRichText } from '../types/ms-forms';
import { RichText } from './RichText';

interface RichTextContentProps {
    content: MsRichText | null | undefined;
    as?: 'div' | 'span';
    className?: string;
}

export function RichTextContent({ content, as = 'div', className }: RichTextContentProps) {
    if (!content) {
        return null;
    }

    if (content.html) {
        return <RichText as={as} className={className} html={content.html} />;
    }

    const Tag = as;

    return <Tag className={className}>{content.text}</Tag>;
}
