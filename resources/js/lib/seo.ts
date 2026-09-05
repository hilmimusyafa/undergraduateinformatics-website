import seoConfig from '../../../seo.json';

export const seoDefaults = {
    title: seoConfig.defaultTitle,
    description: seoConfig.defaultDescription,
} as const;

export type SeoPageKey = keyof typeof seoConfig.pages;

export function seoPage(pageKey: SeoPageKey) {
    return seoConfig.pages[pageKey];
}

export function seoHead(pageKey: SeoPageKey) {
    const { title, description } = seoPage(pageKey);

    return {
        meta: [{ title }, { name: 'description', content: description }] as (
            { title: string } | { name: 'description'; content: string }
        )[],
    };
}
