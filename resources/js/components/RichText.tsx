interface RichTextProps {
    html: string | null | undefined;
    as?: 'div' | 'span';
    className?: string;
}

export function RichText({ html, as = 'div', className }: RichTextProps) {
    if (!html) {
        return null;
    }

    const Tag = as;

    return <Tag className={className} data-rich-text dangerouslySetInnerHTML={{ __html: html }} />;
}
