import { useMemo, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';

import { zodResolver } from '@hookform/resolvers/zod';
import { Loader2 } from 'lucide-react';

import { useMsFormSubmission } from '../hooks/useMsFormSubmission';
import { buildMsFormAnswers, isEmptyAnswer } from '../lib/ms-form-answers';
import {
    computeReachableIds,
    flattenQuestions,
    getSectionIds,
    getSectionQuestions,
    resolveNextSectionId,
} from '../lib/ms-form-branching';
import { buildMsFormDefaultValues, buildMsFormSchema } from '../schemas/ms-forms';
import { type MsFormQuestion, type MsFormSection, type MsFormValues } from '../types/ms-forms';
import { MsFormField } from './MsFormField';
import { MsFormSuccess } from './MsFormStates';
import { PrimaryButton } from './PrimaryButton';
import { SecondaryButton } from './SecondaryButton';
import { FieldDescription, FieldGroup } from './ui/field';

interface MsFormProps {
    questions: MsFormQuestion[];
    sections?: MsFormSection[];
    title: string;
    description: string | null;
    submitUrl: string;
}

export function MsForm({ questions, sections, title, description, submitUrl }: MsFormProps) {
    const schema = useMemo(() => buildMsFormSchema(questions), [questions]);

    const form = useForm<MsFormValues>({
        resolver: zodResolver(schema),
        defaultValues: buildMsFormDefaultValues(questions),
        mode: 'onChange',
    });

    const { control, getValues, setValue } = form;

    const sectionIds = useMemo(() => getSectionIds(sections), [sections]);
    const allQuestions = useMemo(
        () => flattenQuestions(sections, questions),
        [sections, questions]
    );
    const [history, setHistory] = useState<string[]>([sectionIds[0]]);

    const { submitForm, submitError, resetSubmitError } = useMsFormSubmission(
        submitUrl,
        sections,
        questions
    );

    const values = useWatch({ control }) as MsFormValues;

    const currentSectionId = history[history.length - 1];
    const currentSection = sections?.find((section) => section.id === currentSectionId);
    const visibleQuestions = getSectionQuestions(sections, questions, currentSectionId);
    const nextSectionId = resolveNextSectionId(sections, questions, values, currentSectionId);
    const hasNextSection = nextSectionId !== null;
    const isFirstStep = history.length === 1;

    const hasAnyAnswer = useMemo(
        () => buildMsFormAnswers(sections, questions, values).length > 0,
        [sections, questions, values]
    );

    const clearHiddenAnswers = () => {
        const reachable = computeReachableIds(sections, questions, getValues());

        for (const question of allQuestions) {
            if (reachable.has(question.id)) {
                continue;
            }

            const value = getValues(question.id);

            if (!isEmptyAnswer(value)) {
                setValue(question.id, question.multiple ? [] : '', { shouldDirty: true });
            }
        }
    };

    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleNext = async () => {
        const valid = await form.trigger(visibleQuestions.map((question) => question.id));

        if (!valid) {
            return;
        }

        clearHiddenAnswers();

        const nextId = resolveNextSectionId(sections, questions, getValues(), currentSectionId);

        if (nextId === null) {
            return;
        }

        setHistory((current) => [...current, nextId]);
        resetSubmitError();
        scrollToTop();
    };

    const handlePrevious = () => {
        setHistory((current) => current.slice(0, -1));
        resetSubmitError();
        scrollToTop();
    };

    const handleValidSubmit = async () => {
        const reachable = computeReachableIds(sections, questions, getValues());
        const valid = await form.trigger(
            questions
                .filter((question) => reachable.has(question.id))
                .map((question) => question.id)
        );

        if (!valid) {
            return;
        }

        clearHiddenAnswers();
        submitForm.mutate(getValues());
    };

    const handleReset = () => {
        form.reset(buildMsFormDefaultValues(questions));
        setHistory([sectionIds[0]]);
        resetSubmitError();
        submitForm.reset();
        scrollToTop();
    };

    if (submitForm.isSuccess) {
        return <MsFormSuccess onReset={handleReset} />;
    }

    return (
        <div className="typeset typeset-article mx-auto w-full max-w-[37em] px-4 py-10 md:py-9">
            <form noValidate>
                <h1>{title}</h1>
                {description && <p className="text-muted-foreground">{description}</p>}
                {currentSection?.title && <h2>{currentSection.title}</h2>}
                {currentSection?.subtitle && (
                    <p className="text-muted-foreground">{currentSection.subtitle}</p>
                )}

                {visibleQuestions.map((question) => (
                    <section key={question.id}>
                        <h3>
                            {question.title}
                            {question.required && (
                                <span aria-hidden="true" className="text-destructive">
                                    *
                                </span>
                            )}
                        </h3>
                        <FieldGroup>
                            {question.subtitle && (
                                <FieldDescription>{question.subtitle}</FieldDescription>
                            )}
                            <MsFormField question={question} control={control} />
                        </FieldGroup>
                    </section>
                ))}

                {submitError && (
                    <p role="alert" className="text-destructive">
                        {submitError}
                    </p>
                )}

                <div className="mt-10 flex items-center gap-2 md:mt-9">
                    {!isFirstStep && (
                        <SecondaryButton
                            type="button"
                            onClick={handlePrevious}
                            className="flex-1 md:flex-none"
                        >
                            Kembali
                        </SecondaryButton>
                    )}
                    {hasNextSection ? (
                        <PrimaryButton
                            type="button"
                            onClick={() => void handleNext()}
                            className="flex-1 md:flex-none"
                        >
                            Lanjut
                        </PrimaryButton>
                    ) : (
                        <PrimaryButton
                            type="button"
                            onClick={() => void handleValidSubmit()}
                            disabled={submitForm.isPending || !hasAnyAnswer}
                            className="flex-1 md:flex-none"
                        >
                            {submitForm.isPending ? <Loader2 className="animate-spin" /> : null}
                            Kirim
                        </PrimaryButton>
                    )}
                </div>
            </form>
        </div>
    );
}
