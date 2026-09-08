export interface LinkSectionRef {
    id: number;
    name: string;
}

export interface LinkSummary {
    id: number;
    name: string;
    link: string;
    updated_at: string;
    section: LinkSectionRef;
}
