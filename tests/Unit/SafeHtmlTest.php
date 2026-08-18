<?php

namespace Tests\Unit;

use App\Support\SafeHtml;
use PHPUnit\Framework\TestCase;

class SafeHtmlTest extends TestCase
{
    public function test_it_strips_event_handlers_and_javascript_urls(): void
    {
        $dirty = '<p onmouseover="alert(1)">Hello <a href="javascript:alert(1)">click</a> <a href="https://example.com" onclick="evil()">ok</a></p>';

        $clean = SafeHtml::clean($dirty);

        $this->assertStringNotContainsString('onmouseover', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringContainsString('Hello', $clean);
        $this->assertStringContainsString('href="https://example.com"', $clean);
        $this->assertStringContainsString('>ok</a>', $clean);
    }

    public function test_it_adds_noopener_for_blank_targets(): void
    {
        $clean = SafeHtml::clean('<a href="https://example.com" target="_blank">link</a>');

        $this->assertStringContainsString('target="_blank"', $clean);
        $this->assertStringContainsString('rel="noopener noreferrer"', $clean);
    }

    public function test_it_unwraps_disallowed_tags_but_keeps_text(): void
    {
        $clean = SafeHtml::clean('<div><script>alert(1)</script><strong>Safe</strong></div>');

        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsString('<div', $clean);
        $this->assertStringContainsString('<strong>Safe</strong>', $clean);
    }
}
