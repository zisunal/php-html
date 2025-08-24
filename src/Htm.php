<?php

namespace Zisunal\PhpHtml;

use Zisunal\PhpHtml\Base\HtmlBase;

class Htm extends HtmlBase
{

    protected function __construct(){}

    public static function list(array $items, bool $ordered = false, array $wrapper_attrs = [], array $item_attrs = []): self
    {
        $instance = new self();
        $tag = $ordered ? 'ol' : 'ul';
        $instance->tags[] = ["{$tag}_open" => array_merge($wrapper_attrs, ['innerText' => ''])];
        foreach ($items as $item) {
            $instance->tags[] = ['li_open' => array_merge($item_attrs, ['innerText' => $item])];
            $instance->tags[] = ['li_close' => []];
        }
        $instance->tags[] = ["{$tag}_close" => []];
        return $instance;
    }

    public static function table(array $headers, array $rows, array $table_attrs = [], array $tr_attrs = [], array $header_attrs = [], array $cell_attrs = [], bool $horizontal = false): self
    {
        $instance = new self();
        $instance->tags[] = ["table_open" => array_merge($table_attrs, ['innerText' => ''])];
        if (!$horizontal) {
            $instance->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
            foreach ($headers as $header) {
                $instance->tags[] = ['th_open' => array_merge($header_attrs, ['innerText' => $header])];
                $instance->tags[] = ['th_close' => []];
            }
            $instance->tags[] = ['tr_close' => []];
            foreach ($rows as $row) {
                $instance->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
                foreach ($row as $cell) {
                    $instance->tags[] = ['td_open' => array_merge($cell_attrs, ['innerText' => $cell])];
                    $instance->tags[] = ['td_close' => []];
                }
                $instance->tags[] = ['tr_close' => []];
            }
        } else {
            $rows = $instance->transpose($rows);
            foreach ($headers as $index => $header) {
                $instance->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
                $instance->tags[] = ['th_open' => array_merge($header_attrs, ['innerText' => $header])];
                $instance->tags[] = ['th_close' => []];
                foreach ($rows[$index] as $cell) {
                    $instance->tags[] = ['td_open' => array_merge($cell_attrs, ['innerText' => $cell])];
                    $instance->tags[] = ['td_close' => []];
                }
                $instance->tags[] = ['tr_close' => []];
            }
        }
        $instance->tags[] = ["table_close" => []];
        return $instance;
    }

    public static function p(string $paragraph, array $attrs = []): self
    {
        $instance = new self();
        $instance->pOpen($attrs)->innerText($paragraph)->pClose();
        return $instance;
    }

    public static function a(string $href, string $text, string $target = '_self', array $attrs = []): self
    {
        $instance = new self();
        $instance->aOpen(array_merge($attrs, [
            'href' => $href,
            'target' => $target
        ]))->innerText($text)->aClose();
        return $instance;
    }

    public static function img(string $src, string $alt = '', array $attrs = []): self
    {
        $instance = new self();
        $instance->__call('img', ['src' => $src, 'alt' => $alt, 'attrs' => $attrs]);
        return $instance;
    }

    public static function div(array $attrs = [], Html|Htm ...$html): self
    {
        $instance = new self();
        $instance->divOpen($attrs);
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $instance->tags[] = $tag;
            }
        }
        $instance->divClose();
        return $instance;
    }

    public static function iframe(string $src, string $title = '', array $attrs = []): self
    {
        $instance = new self();
        $instance->iframe(array_merge($attrs, [
            'src' => $src,
            'title' => $title
        ]));
        return $instance;
    }

    public static function span(string $innerText = '', array $attrs = [], Html|Htm ...$html): self
    {
        $instance = new self();
        $instance->span($attrs);
        if ($innerText) {
            $instance->tags[] = ['span_inner' => $innerText];
        }
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $instance->tags[] = $tag;
            }
        }
        return $instance;
    }

    public static function button(string $innerText = '', string $type = 'button', array $attrs = [], Html|Htm ...$html): self
    {
        $instance = new self();
        $instance->buttonOpen(array_merge($attrs, ['type' => $type]))->innerText($innerText);
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $instance->tags[] = $tag;
            }
        }
        $instance->buttonClose();
        return $instance;
    }

    public static function i(string $class = '', string $innerText = '', array $attrs = [], Html|Htm ...$html): self
    {
        $instance = new self();
        $instance->iOpen(array_merge($attrs, ['class' => $class]));
        if ($innerText) {
            $instance->innerText($innerText);
        }
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $instance->tags[] = $tag;
            }
        }
        $instance->iClose();
        return $instance;
    }

}
