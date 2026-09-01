import type { Story } from '@ladle/react';

import { PrimaryButton } from './PrimaryButton';
import { SearchBar } from './SearchBar';
import { SecondaryButton } from './SecondaryButton';
import { TextButton } from './TextButton';

export default {
    title: 'Primitives/Buttons',
};

export const Primary: Story = () => <PrimaryButton>Kirim</PrimaryButton>;

export const Secondary: Story = () => <SecondaryButton>Kembali</SecondaryButton>;

export const Search: Story = () => <SearchBar />;

export const TextFade: Story = () => <TextButton variant="fade">Kembali</TextButton>;

export const TextUnderline: Story = () => (
    <TextButton variant="underline" className="text-blue-600">
        Isi Formulir Lagi
    </TextButton>
);
