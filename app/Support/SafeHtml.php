<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

final class SafeHtml
{
    /**
     * @var array<string, list<string>>
     */
    private const ALLOWED = [
        'p' => [],
        'br' => [],
        'strong' => [],
        'b' => [],
        'em' => [],
        'i' => [],
        'u' => [],
        'ul' => [],
        'ol' => [],
        'li' => [],
        'h1' => [],
        'h2' => [],
        'h3' => [],
        'blockquote' => [],
        'code' => [],
        'pre' => [],
        'span' => [],
        'a' => ['href', 'title', 'target', 'rel'],
    ];

    /**
     * @var list<string>
     */
    private const DROP_WITH_CONTENT = [
        'script',
        'style',
        'iframe',
        'object',
        'embed',
        'link',
        'meta',
        'form',
        'input',
        'button',
        'textarea',
        'select',
        'option',
    ];

    public static function clean(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="safe-html-root">'.$html.'</div>',
            LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('safe-html-root');

        if (! $root instanceof DOMElement) {
            return '';
        }

        self::sanitizeChildren($root);

        $output = '';

        foreach (iterator_to_array($root->childNodes) as $child) {
            $output .= $document->saveHTML($child) ?: '';
        }

        return $output;
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child instanceof DOMText) {
                continue;
            }

            if (! $child instanceof DOMElement) {
                $parent->removeChild($child);

                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $parent->removeChild($child);

                continue;
            }

            if (! array_key_exists($tag, self::ALLOWED)) {
                self::sanitizeChildren($child);

                while ($child->firstChild) {
                    $parent->insertBefore($child->firstChild, $child);
                }

                $parent->removeChild($child);

                continue;
            }

            self::sanitizeAttributes($child, self::ALLOWED[$tag]);
            self::sanitizeChildren($child);
        }
    }

    /**
     * @param  list<string>  $allowedAttributes
     */
    private static function sanitizeAttributes(DOMElement $element, array $allowedAttributes): void
    {
        $allowed = array_fill_keys($allowedAttributes, true);

        foreach (iterator_to_array($element->attributes ?? []) as $attribute) {
            $name = strtolower($attribute->name);

            if (! isset($allowed[$name]) || str_starts_with($name, 'on')) {
                $element->removeAttribute($attribute->name);

                continue;
            }

            if ($name === 'href') {
                $href = trim($attribute->value);

                if (! self::isSafeHref($href)) {
                    $element->removeAttribute($attribute->name);

                    continue;
                }

                $element->setAttribute('href', $href);
            }

            if ($name === 'target') {
                $target = strtolower(trim($attribute->value));

                if ($target !== '_blank') {
                    $element->removeAttribute($attribute->name);

                    continue;
                }

                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }

            if ($name === 'rel') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        if (strtolower($element->tagName) === 'a' && $element->getAttribute('target') === '_blank') {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function isSafeHref(string $href): bool
    {
        if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, '/')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto'], true);
    }
}
