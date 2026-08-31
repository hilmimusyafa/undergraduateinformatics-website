<?php

namespace Tests\Unit\Services;

use App\Services\MsForms\FormDefinitionNormalizer;
use PHPUnit\Framework\TestCase;

final class FormDefinitionNormalizerTest extends TestCase
{
    public function testNormalizesSectionsAndBranching(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/msforms/form-definition-branching.raw.json'),
            true
        );

        $result = (new FormDefinitionNormalizer())->normalize($raw);

        $this->assertSame([
            [
                'id' => 'p1',
                'title' => 'Kerahasiaan Identitas',
                'subtitle' => 'Pilih cara Anda menyampaikan masukan.',
                'questionIds' => ['q1'],
            ],
            [
                'id' => 'p2',
                'title' => 'IDENTITAS',
                'subtitle' => 'Isi identitas agar dapat ditindaklanjuti.',
                'questionIds' => ['q2'],
            ],
            [
                'id' => 'p3',
                'title' => 'PENGADUAN',
                'subtitle' => 'Sampaikan masukan Anda di sini.',
                'questionIds' => ['q3'],
            ],
        ], $result['sections']);

        $q1 = $result['questions'][0];
        $this->assertSame('end', $q1['choices'][0]['branchTargetId']);
        $this->assertSame('q2', $q1['choices'][1]['branchTargetId']);

        $q3 = $result['questions'][2];
        $this->assertNull($q3['choices'][0]['branchTargetId']);
        $this->assertArrayNotHasKey('branchInfo', $q3['choices'][0]);
    }

    public function testNormalizesGenuineFormDefinition(): void
    {
        $raw = json_decode(
            file_get_contents(__DIR__ . '/../../Fixtures/msforms/form-definition.raw.json'),
            true
        );

        $result = (new FormDefinitionNormalizer())->normalize($raw);

        $this->assertSame('this is form title', $result['title']);

        $this->assertSame([
            [
                'id' => 'r5ea034e6b67a462ba2a1ff857fad2490',
                'title' => 'this is section title',
                'subtitle' => 'this is section subtitle',
                'questionIds' => ['rd7645a06d5f94664917ff0617f123de3'],
            ],
            [
                'id' => 'r988954e7af514c56b64d4d9fc107b58d',
                'title' => 'this is the next section title that doesnt have subtitle',
                'subtitle' => null,
                'questionIds' => ['rb38b17fd578e4dfbb6b32d32f4dfc885'],
            ],
            [
                'id' => 'rb0f93bfc937f4ae299b422a9b623e280',
                'title' => null,
                'subtitle' => null,
                'questionIds' => ['r729590f423cd4629a005ea35c177fa59'],
            ],
        ], $result['sections']);

        $this->assertSame('text', $result['questions'][0]['type']);
        $this->assertSame('date', $result['questions'][1]['type']);
        $this->assertSame('choice', $result['questions'][2]['type']);

        $choice = $result['questions'][2];
        $this->assertSame(['Option 1', 'Option 2'], array_column($choice['choices'], 'value'));
        $this->assertNull($choice['choices'][0]['branchTargetId']);
        $this->assertArrayNotHasKey('branchInfo', $choice['choices'][0]);
    }

    public function testSingleSectionWhenNoSectionInfo(): void
    {
        $raw = [
            'title' => 'T',
            'questions' => [
                ['id' => 'a', 'type' => 'Question.TextField', 'title' => 'A', 'questionInfo' => null],
            ],
        ];

        $result = (new FormDefinitionNormalizer())->normalize($raw);

        $this->assertSame('section-1', $result['sections'][0]['id']);
        $this->assertSame(['a'], $result['sections'][0]['questionIds']);
    }

    public function testEmptySectionTitlesAndSubtitlesNormalizeToNull(): void
    {
        $raw = [
            'title' => 'T',
            'description' => null,
            'descriptiveQuestions' => [
                ['id' => 'p1', 'type' => 'Question.ColumnGroup', 'title' => '', 'subtitle' => '', 'order' => 1],
                ['id' => 'p2', 'type' => 'Question.ColumnGroup', 'title' => null, 'subtitle' => null, 'order' => 2],
            ],
            'questions' => [],
        ];

        $result = (new FormDefinitionNormalizer())->normalize($raw);

        $this->assertNull($result['sections'][0]['title']);
        $this->assertNull($result['sections'][0]['subtitle']);
        $this->assertNull($result['sections'][1]['title']);
        $this->assertNull($result['sections'][1]['subtitle']);
        $this->assertSame([], $result['sections'][0]['questionIds']);
    }
}