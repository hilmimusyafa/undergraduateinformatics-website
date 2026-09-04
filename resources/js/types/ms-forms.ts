export type MsFormBranchTarget = string | 'end' | null;

export interface MsRichText {
    text: string;
    html?: string | null;
}

export interface MsFormChoice {
    value: string;
    label: MsRichText;
    branchTargetId: MsFormBranchTarget;
}

export interface MsFormSection {
    id: string;
    title: MsRichText | null;
    subtitle: MsRichText | null;
    questionIds: string[];
}

export interface MsFormQuestion {
    id: string;
    title: MsRichText;
    subtitle: MsRichText | null;
    type: 'text' | 'choice' | 'date';
    required: boolean;
    multiple: boolean;
    choices: MsFormChoice[];
}

export interface MsFormPayload {
    link: string;
    title: MsRichText;
    description: MsRichText | null;
    sections?: MsFormSection[];
    questions: MsFormQuestion[];
}

export type MsFormAnswer = string | string[];

export type MsFormValues = Record<string, MsFormAnswer>;
