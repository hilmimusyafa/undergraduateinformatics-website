<?php

namespace App\Services\MsForms;

final class FormDefinitionNormalizer
{
    private const TYPE_MAP = [
        'Question.TextField' => 'text',
        'Question.Choice' => 'choice',
        'Question.DateTime' => 'date',
    ];

    private RichTextSanitizer $sanitizer;

    public function __construct(?RichTextSanitizer $sanitizer = null)
    {
        $this->sanitizer = $sanitizer ?? new RichTextSanitizer;
    }

    private function richText(?string $plain, ?string $rich): ?array
    {
        if ($plain === null) {
            return null;
        }

        return [
            'text' => $plain,
            'html' => $this->sanitizer->sanitizeRich($rich),
        ];
    }

    public function normalize(array $raw): array
    {
        $questions = [];
        $items = [];

        foreach ($raw['descriptiveQuestions'] ?? [] as $descriptive) {
            if (($descriptive['type'] ?? null) !== 'Question.ColumnGroup') {
                continue;
            }

            $items[] = [
                'kind' => 'section',
                'id' => $descriptive['id'] ?? '',
                'title' => $this->richText(($descriptive['title'] ?? '') ?: null, $descriptive['formsProRTQuestionTitle'] ?? null),
                'subtitle' => $this->richText(($descriptive['subtitle'] ?? '') ?: null, $descriptive['formsProRTSubtitle'] ?? null),
                'order' => $descriptive['order'] ?? 0,
            ];
        }

        foreach ($raw['questions'] ?? [] as $question) {
            $normalized = $this->normalizeQuestion($question);

            if ($normalized === null) {
                continue;
            }

            $questions[] = $normalized;
            $items[] = [
                'kind' => 'question',
                'id' => $normalized['id'],
                'order' => $question['order'] ?? 0,
            ];
        }

        $sections = $this->buildSections($items);

        $this->resolveBranchTargets($questions, $sections);

        return [
            'title' => $this->richText($raw['title'] ?? '', $raw['formsProRTTitle'] ?? null),
            'description' => $this->richText(($raw['description'] ?? '') ?: null, $raw['formsProRTDescription'] ?? null),
            'sections' => $sections,
            'questions' => $questions,
        ];
    }

    private function buildSections(array $items): array
    {
        usort($items, static fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        $sections = [];
        $sectionIndex = -1;

        foreach ($items as $item) {
            if ($item['kind'] === 'section') {
                $sections[] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'] ?? null,
                    'questionIds' => [],
                ];
                $sectionIndex = count($sections) - 1;

                continue;
            }

            if ($sectionIndex < 0) {
                $sections[] = [
                    'id' => 'section-1',
                    'title' => null,
                    'subtitle' => null,
                    'questionIds' => [],
                ];
                $sectionIndex = 0;
            }

            $sections[$sectionIndex]['questionIds'][] = $item['id'];
        }

        if ($sections === []) {
            $sections[] = ['id' => 'section-1', 'title' => null, 'subtitle' => null, 'questionIds' => []];
        }

        return $sections;
    }

    private function resolveBranchTargets(array &$questions, array $sections): void
    {
        $firstQuestionBySectionId = [];
        $sectionByQuestionId = [];

        foreach ($sections as $section) {
            $firstQuestionBySectionId[$section['id']] = $section['questionIds'][0] ?? null;

            foreach ($section['questionIds'] as $questionId) {
                $sectionByQuestionId[$questionId] = $section['id'];
            }
        }

        foreach ($questions as &$question) {
            foreach ($question['choices'] as &$choice) {
                $branchInfo = $choice['branchInfo'] ?? null;

                $choice['branchTargetId'] = $this->normalizeBranchTarget(
                    $branchInfo,
                    $firstQuestionBySectionId,
                    $sectionByQuestionId
                );

                unset($choice['branchInfo']);
            }
            unset($choice);
        }
        unset($question);
    }

    private function normalizeBranchTarget(
        ?array $branchInfo,
        array $firstQuestionBySectionId,
        array $sectionByQuestionId,
    ): ?string {
        if ($branchInfo === null) {
            return null;
        }

        if (($branchInfo['ToTheEnd'] ?? false) === true) {
            return 'end';
        }

        $target = $branchInfo['TargetQuestionId'] ?? null;

        if (! is_string($target) || $target === '') {
            return null;
        }

        if (isset($firstQuestionBySectionId[$target]) && $firstQuestionBySectionId[$target] !== null) {
            return $firstQuestionBySectionId[$target];
        }

        if (isset($sectionByQuestionId[$target])) {
            return $target;
        }

        return null;
    }

    private function normalizeQuestion(array $question): ?array
    {
        $type = $question['type'] ?? null;
        $normalizedType = self::TYPE_MAP[$type] ?? null;

        if ($normalizedType === null) {
            return null;
        }

        $result = [
            'id' => $question['id'] ?? '',
            'title' => $this->richText($question['title'] ?? '', $question['formsProRTQuestionTitle'] ?? null),
            'subtitle' => $this->richText(($question['subtitle'] ?? '') ?: null, $question['formsProRTSubtitle'] ?? null),
            'type' => $normalizedType,
            'required' => (bool) ($question['required'] ?? false),
            'multiple' => false,
            'choices' => [],
        ];

        if ($normalizedType === 'choice') {
            $info = json_decode($question['questionInfo'] ?? '{}', true);

            if (! is_array($info)) {
                return null;
            }

            $result['multiple'] = (bool) ($question['allowMultipleValues'] ?? false);

            foreach ($info['Choices'] ?? [] as $choice) {
                $description = $choice['Description'] ?? '';

                if ($description === '') {
                    continue;
                }

                $result['choices'][] = [
                    'value' => $description,
                    'label' => [
                        'text' => trim(strip_tags($description)),
                        'html' => $this->sanitizer->sanitizeRich($description),
                    ],
                    'branchInfo' => $choice['BranchInfo'] ?? null,
                ];
            }

            if ($result['choices'] === []) {
                return null;
            }
        }

        return $result;
    }
}
