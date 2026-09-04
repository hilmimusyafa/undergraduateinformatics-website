import { useMemo, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';

import { zodResolver } from '@hookform/resolvers/zod';
import { Loader2 } from 'lucide-react';

import { ArticleContainer } from '../components/ArticleContainer';
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
import {
    type MsFormQuestion,
    type MsFormSection,
    type MsFormValues,
    type MsRichText,
} from '../types/ms-forms';
import { MsFormField } from './MsFormField';
import { MsFormSuccess } from './MsFormStates';
import { PrimaryButton } from './PrimaryButton';
import { RichText } from './RichText';
import { RichTextContent } from './RichTextContent';
import { SecondaryButton } from './SecondaryButton';
import { FieldDescription, FieldGroup } from './ui/field';

interface MsFormProps {
    questions: MsFormQuestion[];
    sections?: MsFormSection[];
    title: MsRichText;
    description: MsRichText | null;
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
    const [emptySubmitTried, setEmptySubmitTried] = useState(false);

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

    const scrollToFirstInvalid = (questions: MsFormQuestion[]) => {
        const result = buildMsFormSchema(questions).safeParse(getValues());
        const invalidIds = new Set(
            result.success ? [] : result.error.issues.map((issue) => issue.path[0])
        );
        const invalidId = questions.find((question) => invalidIds.has(question.id))?.id;

        if (invalidId) {
            document
                .querySelector(`[data-question-id="${invalidId}"]`)
                ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    const handleNext = async () => {
        const valid = await form.trigger(visibleQuestions.map((question) => question.id));

        if (!valid) {
            scrollToFirstInvalid(visibleQuestions);
            return;
        }

        clearHiddenAnswers();

        const nextId = resolveNextSectionId(sections, questions, getValues(), currentSectionId);

        if (nextId === null) {
            return;
        }

        setHistory((current) => [...current, nextId]);
        resetSubmitError();
        setEmptySubmitTried(false);
        scrollToTop();
    };

    const handlePrevious = () => {
        setHistory((current) => current.slice(0, -1));
        resetSubmitError();
        setEmptySubmitTried(false);
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
            scrollToFirstInvalid(visibleQuestions);
            return;
        }

        if (buildMsFormAnswers(sections, questions, getValues()).length === 0) {
            setEmptySubmitTried(true);
            return;
        }

        clearHiddenAnswers();
        submitForm.mutate(getValues());
    };

    const handleReset = () => {
        form.reset(buildMsFormDefaultValues(questions));
        setHistory([sectionIds[0]]);
        resetSubmitError();
        setEmptySubmitTried(false);
        submitForm.reset();
        scrollToTop();
    };

    if (submitForm.isSuccess) {
        return <MsFormSuccess onReset={handleReset} />;
    }

    return (
        <ArticleContainer className="max-w-[37em]">
            <form noValidate>
                <h1>
                    <RichTextContent content={title} as="span" />
                </h1>
                {description && (
                    <RichTextContent
                        content={description}
                        as="div"
                        className="text-muted-foreground"
                    />
                )}
                {currentSection?.title && (
                    <h2>
                        <RichTextContent content={currentSection.title} as="span" />
                    </h2>
                )}
                {currentSection?.subtitle && (
                    <RichTextContent
                        content={currentSection.subtitle}
                        as="div"
                        className="text-muted-foreground"
                    />
                )}

                {visibleQuestions.map((question) => (
                    <section key={question.id} data-question-id={question.id}>
                        <h3>
                            <RichTextContent content={question.title} as="span" />
                            {question.required && (
                                <span aria-hidden="true" className="text-destructive">
                                    *
                                </span>
                            )}
                        </h3>
                        <FieldGroup>
                            {question.subtitle &&
                                (question.subtitle.text || question.subtitle.html) &&
                                (question.subtitle.html ? (
                                    <RichText
                                        as="div"
                                        className="text-muted-foreground [&>a:hover]:text-primary text-left text-base leading-normal font-normal group-has-data-horizontal/field:text-balance last:mt-0 nth-last-2:-mt-1 [&>a]:underline [&>a]:underline-offset-4 [[data-variant=legend]+&]:-mt-1.5"
                                        html={question.subtitle.html}
                                    />
                                ) : (
                                    <FieldDescription>{question.subtitle.text}</FieldDescription>
                                ))}
                            <MsFormField question={question} control={control} />
                        </FieldGroup>
                    </section>
                ))}

                {submitError && (
                    <p role="alert" className="text-destructive">
                        {submitError}
                    </p>
                )}

                {emptySubmitTried && !hasAnyAnswer && (
                    <p role="alert" className="text-destructive">
                        Isi minimal satu jawaban terlebih dahulu.
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
                            disabled={submitForm.isPending}
                            className="flex-1 md:flex-none"
                        >
                            {submitForm.isPending ? <Loader2 className="animate-spin" /> : null}
                            Kirim
                        </PrimaryButton>
                    )}
                </div>
            </form>
        </ArticleContainer>
    );
}
