import type { VariantProps } from 'class-variance-authority';

import { buttonVariants } from './ui/button';

export type TextVariant = 'fade' | 'underline';

export const textButtonVariant: Record<
    TextVariant,
    NonNullable<VariantProps<typeof buttonVariants>['variant']>
> = {
    fade: 'ghost',
    underline: 'link',
};
