<?php

namespace Tests\Unit\Services;

use App\Services\MsForms\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

final class RichTextSanitizerTest extends TestCase
{
    private RichTextSanitizer $sanitizer;

    protected function setUp(): void
    {
        $this->sanitizer = new RichTextSanitizer;
    }

    public function test_removes_scripts_and_event_handlers(): void
    {
        $result = $this->sanitizer->sanitize('<script>alert(1)</script><b onclick="alert(1)">teks</b>');

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('<script', $result);
        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringContainsString('teks', $result);
    }

    public function test_strips_javascript_urls(): void
    {
        $result = $this->sanitizer->sanitize('<a href="javascript:alert(1)">klik</a>');

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('javascript:', $result);
    }

    public function test_keeps_whitelisted_formatting(): void
    {
        $result = $this->sanitizer->sanitize('<p><b>tebal</b> <i>miring</i> <u>garis</u> <s>coret</s></p>');

        $this->assertStringContainsString('<b>tebal</b>', $result);
        $this->assertStringContainsString('<i>miring</i>', $result);
        $this->assertStringContainsString('<u>garis</u>', $result);
        $this->assertStringContainsString('coret', $result);
    }

    public function test_keeps_lists_and_links(): void
    {
        $result = $this->sanitizer->sanitize(
            '<ul><li>satu</li><li>dua</li></ul><a href="https://example.com">link</a>'
        );

        $this->assertStringContainsString('<ul>', $result);
        $this->assertStringContainsString('<li>satu</li>', $result);
        $this->assertStringContainsString('https://example.com', $result);
        $this->assertStringContainsString('target="_blank"', $result);
        $this->assertStringContainsString('noopener', $result);
        $this->assertStringContainsString('noreferrer', $result);
    }

    public function test_removes_inline_styles(): void
    {
        $result = $this->sanitizer->sanitize('<span style="color:red">x</span>');

        $this->assertNotNull($result);
        $this->assertStringNotContainsString('style=', $result);
        $this->assertStringContainsString('x', $result);
    }

    public function test_unwraps_single_outer_block_wrapper(): void
    {
        $this->assertSame('<b>isi</b>', $this->sanitizer->sanitize('<div><b>isi</b></div>'));
        $this->assertSame('teks', $this->sanitizer->sanitize('<p>teks</p>'));
    }

    public function test_keeps_adjacent_block_wrappers_well_formed(): void
    {
        $this->assertSame('<p>a</p><p>b</p>', $this->sanitizer->sanitize('<p>a</p><p>b</p>'));
        $this->assertSame('<div>x</div><div>y</div>', $this->sanitizer->sanitize('<div>x</div><div>y</div>'));
    }

    public function test_keeps_mixed_block_structure_well_formed(): void
    {
        $this->assertSame(
            '<p>intro</p><ul><li>a</li></ul><p>outro</p>',
            $this->sanitizer->sanitize('<p>intro</p><ul><li>a</li></ul><p>outro</p>')
        );
    }

    public function test_unwraps_nested_block_wrapper(): void
    {
        $this->assertSame('<p>paragraf</p>', $this->sanitizer->sanitize('<div><p>paragraf</p></div>'));
    }

    public function test_keeps_nested_block_structure_for_block_contexts(): void
    {
        $result = $this->sanitizer->sanitize('<p>Paragraf</p><ul><li>a</li></ul>');

        $this->assertStringContainsString('<p>Paragraf</p>', $result);
        $this->assertStringContainsString('<li>a</li>', $result);
    }

    public function test_returns_null_for_null_or_empty_input(): void
    {
        $this->assertNull($this->sanitizer->sanitize(null));
        $this->assertNull($this->sanitizer->sanitize(''));
        $this->assertNull($this->sanitizer->sanitize('<script></script>'));
    }

    public function test_detects_rich_content(): void
    {
        $this->assertTrue($this->sanitizer->isRich('<b>tebal</b>'));
        $this->assertTrue($this->sanitizer->isRich('<p>paragraf</p>'));
        $this->assertFalse($this->sanitizer->isRich('teks polos'));
    }

    public function test_sanitize_rich_returns_null_for_plain_text(): void
    {
        $this->assertNull($this->sanitizer->sanitizeRich('teks polos'));
        $this->assertNull($this->sanitizer->sanitizeRich(null));
    }

    public function test_sanitize_rich_keeps_rich_markup(): void
    {
        $this->assertSame('<b>tebal</b>', $this->sanitizer->sanitizeRich('<b>tebal</b>'));
        $this->assertSame('Halo <i>dunia</i>', $this->sanitizer->sanitizeRich('<p>Halo <i>dunia</i></p>'));
    }
}
