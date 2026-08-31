import {
    type MsFormAnswer,
    type MsFormBranchTarget,
    type MsFormQuestion,
    type MsFormSection,
    type MsFormValues,
} from '../types/ms-forms';

export const IMPLICIT_SECTION_ID = 'section-1';

export function getSectionIds(sections: MsFormSection[] | undefined): string[] {
    if (sections && sections.length > 0) {
        return sections.map((section) => section.id);
    }

    return [IMPLICIT_SECTION_ID];
}

function questionsById(ids: string[], questions: MsFormQuestion[]): MsFormQuestion[] {
    const byId = new Map(questions.map((question) => [question.id, question]));

    return ids
        .map((id) => byId.get(id))
        .filter((question): question is MsFormQuestion => question !== undefined);
}

export function getSectionQuestions(
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[],
    sectionId: string
): MsFormQuestion[] {
    if (!sections || sections.length === 0) {
        return questions;
    }

    const section = sections.find((candidate) => candidate.id === sectionId);

    if (!section) {
        return [];
    }

    return questionsById(section.questionIds, questions);
}

export function flattenQuestions(
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[]
): MsFormQuestion[] {
    if (!sections || sections.length === 0) {
        return questions;
    }

    return sections.flatMap((section) => questionsById(section.questionIds, questions));
}

function resolveBranchTarget(
    question: MsFormQuestion,
    answer: MsFormAnswer | undefined
): MsFormBranchTarget {
    if (question.type !== 'choice' || question.multiple || typeof answer !== 'string') {
        return null;
    }

    const choice = question.choices.find((candidate) => candidate.value === answer);

    if (!choice?.branchTargetId) {
        return null;
    }

    return choice.branchTargetId;
}

function questionSectionId(
    question: MsFormQuestion,
    sections: MsFormSection[] | undefined
): string {
    if (sections && sections.length > 0) {
        const section = sections.find((candidate) => candidate.questionIds.includes(question.id));

        if (section) {
            return section.id;
        }
    }

    return IMPLICIT_SECTION_ID;
}

export function resolveNextSectionId(
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[],
    answers: MsFormValues,
    currentSectionId: string
): string | null {
    const sectionIds = getSectionIds(sections);
    const currentIndex = sectionIds.indexOf(currentSectionId);

    if (currentIndex < 0) {
        return null;
    }

    for (const question of getSectionQuestions(sections, questions, currentSectionId)) {
        const target = resolveBranchTarget(question, answers[question.id]);

        if (target === 'end') {
            return null;
        }

        if (target) {
            const targetQuestion = questions.find((candidate) => candidate.id === target);

            if (!targetQuestion) {
                return null;
            }

            return questionSectionId(targetQuestion, sections);
        }
    }

    const nextIndex = currentIndex + 1;

    if (nextIndex >= sectionIds.length) {
        return null;
    }

    return sectionIds[nextIndex];
}

export function computeReachableIds(
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[],
    answers: MsFormValues
): Set<string> {
    const sectionIds = getSectionIds(sections);
    const reachable = new Set<string>();
    const visited = new Set<string>();
    let currentSectionId: string | undefined = sectionIds[0];

    while (currentSectionId !== undefined) {
        if (visited.has(currentSectionId)) {
            break;
        }

        visited.add(currentSectionId);

        for (const question of getSectionQuestions(sections, questions, currentSectionId)) {
            reachable.add(question.id);
        }

        currentSectionId =
            resolveNextSectionId(sections, questions, answers, currentSectionId) ?? undefined;
    }

    return reachable;
}
