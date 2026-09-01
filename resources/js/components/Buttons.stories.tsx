import type { Story } from '@ladle/react';

import { PrimaryButton } from './PrimaryButton';
import { SearchBar } from './SearchBar';
import { SecondaryButton } from './SecondaryButton';

export default {
    title: 'Primitives/Buttons',
};

export const Primary: Story = () => <PrimaryButton>Kirim</PrimaryButton>;

export const Secondary: Story = () => <SecondaryButton>Kembali</SecondaryButton>;

export const Search: Story = () => <SearchBar />;
