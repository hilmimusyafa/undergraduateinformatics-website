import type { Story, StoryDefault } from '@ladle/react';

import { TableOfContents } from '@/components/TableOfContents';

import { MainAsideLayout } from './MainAsideLayout';

const tocItems = [
    { id: 'section-1', label: 'Akademik' },
    { id: 'section-2', label: 'MBKM' },
    { id: 'section-3', label: 'Alumni' },
];

export default {
    title: 'Main Aside Layout',
} satisfies StoryDefault;

export const Default: Story = () => (
    <MainAsideLayout
        mainContent={
            <>
                <h1>Judul Halaman</h1>
                <p className="text-muted-foreground">Deskripsi halaman.</p>
                <h2 id="section-1" className="scroll-mt-28 md:scroll-mt-27">
                    Akademik
                </h2>
                <p>Konten section akademik.</p>
                <h2 id="section-2" className="scroll-mt-28 md:scroll-mt-27">
                    MBKM
                </h2>
                <p>Konten section MBKM.</p>
                <h2 id="section-3" className="scroll-mt-28 md:scroll-mt-27">
                    Alumni
                </h2>
                <p>Konten section alumni.</p>
            </>
        }
        asideContent={<TableOfContents items={tocItems} onSelect={() => undefined} />}
    />
);
