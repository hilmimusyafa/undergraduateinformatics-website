export type MsFormBranchTarget = string | 'end' | null;

export interface MsFormChoice {
    value: string;
    label: string;
    branchTargetId: MsFormBranchTarget;
}

export interface MsFormSection {
    id: string;
    title: string | null;
    subtitle: string | null;
    questionIds: string[];
}

export interface MsFormQuestion {
    id: string;
    title: string;
    subtitle: string | null;
    type: 'text' | 'choice' | 'date';
    required: boolean;
    multiple: boolean;
    choices: MsFormChoice[];
}

export interface MsFormPayload {
    link: string;
    title: string;
    description: string | null;
    sections?: MsFormSection[];
    questions: MsFormQuestion[];
}

export type MsFormAnswer = string | string[];

export type MsFormValues = Record<string, MsFormAnswer>;
