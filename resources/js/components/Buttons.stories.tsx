import type { Story } from '@ladle/react';

import { PrimaryButton } from './PrimaryButton';
import { RouterHarness } from './RouterHarness';
import { SearchBar } from './SearchBar';
import { SecondaryButton } from './SecondaryButton';
import { TextButton } from './TextButton';
import { TextLink } from './TextLink';

export default {
    title: 'Primitives/Buttons',
};

export const Primary: Story = () => <PrimaryButton>Kirim</PrimaryButton>;

export const Secondary: Story = () => <SecondaryButton>Kembali</SecondaryButton>;

export const Search: Story = () => <SearchBar />;

export const TextButtonFade: Story = () => <TextButton variant="fade">Kembali</TextButton>;

export const TextButtonUnderline: Story = () => (
    <TextButton variant="underline">Isi Formulir Lagi</TextButton>
);

export const TextLinkFade: Story = () => (
    <RouterHarness>
        <TextLink variant="fade" to="/">
            Kembali
        </TextLink>
    </RouterHarness>
);

export const TextLinkUnderline: Story = () => (
    <RouterHarness>
        <TextLink variant="underline" to="/">
            Isi Formulir Lagi
        </TextLink>
    </RouterHarness>
);
