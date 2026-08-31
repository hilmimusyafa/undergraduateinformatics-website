import {
    type MsFormAnswer,
    type MsFormQuestion,
    type MsFormSection,
    type MsFormValues,
} from '../types/ms-forms';
import { computeReachableIds } from './ms-form-branching';

export interface MsFormAnswerEntry {
    questionId: string;
    answer: MsFormAnswer;
}

export function buildMsFormAnswers(
    sections: MsFormSection[] | undefined,
    questions: MsFormQuestion[],
    values: MsFormValues
): MsFormAnswerEntry[] {
    const reachable = computeReachableIds(sections, questions, values);

    return questions
        .filter((question) => reachable.has(question.id))
        .map((question) => ({ questionId: question.id, answer: values[question.id] }))
        .filter(
            (entry): entry is MsFormAnswerEntry =>
                entry.answer !== undefined &&
                (typeof entry.answer === 'string'
                    ? entry.answer.trim() !== ''
                    : entry.answer.length > 0)
        );
}
