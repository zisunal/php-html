<?php

namespace Zisunal\PhpHtml;

use Zisunal\PhpHtml\Interfaces\HtmlInterface;
use Zisunal\PhpHtml\Htm;
use Zisunal\PhpHtml\Base\HtmlBase;

class Html extends HtmlBase implements HtmlInterface
{
    protected array $html_template = [];

    public function __construct(array $html_template = [], bool $minify = false)
    {
        $this->html_template = $html_template;
        $this->minify = $minify;
    }

    public function title(string $text): self
    {
        $this->html_template['title'] = $text;
        return $this;
    }

    public function favicon(string $source): self
    {
        $this->html_template['favicon'] = $source;
        return $this;
    }

    public function removeFavicon(): self
    {
        unset($this->html_template['favicon']);
        return $this;
    }

    public function addCssFile(string $source, string|null $id, string|null $rel, string|null $integrity, bool|string $crossorigin = false): self
    {
        if (empty($id)) {
            $id = uniqid('css_');
        }
        $this->html_template['css_files'][$id] = $source;
        if (!empty($rel)) {
            $this->html_template['css_rel'][$id] = $rel;
        }
        if ($crossorigin !== false) {
            $this->html_template['css_crossorigin'][$id] = $crossorigin;
        }
        if (!empty($integrity)) {
            $this->html_template['css_integrity'][$id] = $integrity;
        }
        return $this;
    }

    public function removeCssFile(string $id): self
    {
        unset($this->html_template['css_files'][$id]);
        return $this;
    }

    public function addMeta(string $name, string $content): self
    {
        $this->html_template['meta_tags'][] = ['name' => $name, 'content' => $content];
        return $this;
    }

    public function meta(array $attrs): self
    {
        $this->html_template['meta_tags'][] = $attrs;
        return $this;
    }

    public function removeMeta(string $name): self
    {
        foreach ($this->html_template['meta_tags'] as $key => $meta) {
            if ($meta['name'] === $name) {
                unset($this->html_template['meta_tags'][$key]);
                break;
            }
        }
        return $this;
    }

    public function updateMeta(string $name, string $content): self
    {
        foreach ($this->html_template['meta_tags'] as $key => $meta) {
            if ($meta['name'] === $name) {
                $this->html_template['meta_tags'][$key]['content'] = $content;
                break;
            }
        }
        return $this;
    }

    public function addJsFile(string $source, string|null $id, string|null $integrity, string|null $type, bool $header = false, bool $crossorigin = false): self
    {
        if (empty($id)) {
            $id = uniqid('js_');
        }
        if ($header) {
            $this->html_template['header_js_files'][$id] = $source;
        } else {
            $this->html_template['footer_js_files'][$id] = $source;
        }
        if (!empty($integrity)) {
            $this->html_template['js_integrity'][$id] = $integrity;
        }
        if ($crossorigin !== false) {
            $this->html_template['js_crossorigin'][$id] = $crossorigin;
        }
        if (!empty($type)) {
            $this->html_template['js_type'][$id] = $type;
        }
        return $this;
    }

    public function removeJsFile(string $id): self
    {
        unset($this->html_template['header_js_files'][$id]);
        unset($this->html_template['footer_js_files'][$id]);
        return $this;
    }

    public function css(string $selector, array $styles): self
    {
        $this->html_template['inner_css'][$selector] = $styles;
        return $this;
    }

    public function js(string $code, bool $header = false): self
    {
        if ($header) {
            $this->html_template['inner_js_header'] = $code;
        } else {
            $this->html_template['inner_js_footer'] = $code;
        }
        return $this;
    }

    public function htmlAttrs(array $attrs): self
    {
        $this->html_template['html_attrs'] = $attrs;
        return $this;
    }

    public function bodyAttrs(array $attrs): self
    {
        $this->html_template['body_attrs'] = $attrs;
        return $this;
    }

    public function list(array $items, bool $ordered = false, array $wrapper_attrs = [], array $item_attrs = []): self
    {
        $tag = $ordered ? 'ol' : 'ul';
        $this->tags[] = ["{$tag}_open" => array_merge($wrapper_attrs, ['innerText' => ''])];
        foreach ($items as $item) {
            $this->tags[] = ['li_open' => array_merge($item_attrs, ['innerText' => $item])];
            $this->tags[] = ['li_close' => []];
        }
        $this->tags[] = ["{$tag}_close" => []];
        return $this;
    }

    public function table(array $headers, array $rows, array $table_attrs = [], array $tr_attrs = [], array $header_attrs = [], array $cell_attrs = [], bool $horizontal = false): self
    {
        $this->tags[] = ["table_open" => array_merge($table_attrs, ['innerText' => ''])];
        if (!$horizontal) {
            $this->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
            foreach ($headers as $header) {
                $this->tags[] = ['th_open' => array_merge($header_attrs, ['innerText' => $header])];
                $this->tags[] = ['th_close' => []];
            }
            $this->tags[] = ['tr_close' => []];
            foreach ($rows as $row) {
                $this->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
                foreach ($row as $cell) {
                    $this->tags[] = ['td_open' => array_merge($cell_attrs, ['innerText' => $cell])];
                    $this->tags[] = ['td_close' => []];
                }
                $this->tags[] = ['tr_close' => []];
            }
        } else {
            $rows = $this->transpose($rows);
            foreach ($headers as $index => $header) {
                $this->tags[] = ['tr_open' => array_merge($tr_attrs, ['innerText' => ''])];
                $this->tags[] = ['th_open' => array_merge($header_attrs, ['innerText' => $header])];
                $this->tags[] = ['th_close' => []];
                foreach ($rows[$index] as $cell) {
                    $this->tags[] = ['td_open' => array_merge($cell_attrs, ['innerText' => $cell])];
                    $this->tags[] = ['td_close' => []];
                }
                $this->tags[] = ['tr_close' => []];
            }
        }
        $this->tags[] = ["table_close" => []];
        return $this;
    }

    public function p(string $paragraph, array $attrs = []): self
    {
        return $this->pOpen($attrs)->innerText($paragraph)->pClose();
    }

    public function a(string $href, string $text, string $target = '', array $attrs = []): self
    {
        return $this->aOpen(array_merge($attrs, ['href' => $href, 'target' => $target]))->innerText($text)->aClose();
    }

    public function img(string $src, string $alt = '', array $attrs = []): self
    {
        return $this->__call('img', [array_merge($attrs, ['src' => $src, 'alt' => $alt])]);
    }

    public function div(array $attrs = [], Html|Htm ...$html): self
    {
        $this->tags[] = ['div_open' => $attrs];
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $this->tags[] = $tag;
            }
        }
        $this->tags[] = ['div_close' => []];
        $this->lastTag = 'div';
        return $this;
    }
    
    public function iframe(string $src, string $title = '', array $attrs = []): self
    {
        return $this->__call('iframe', array_merge($attrs, ['src' => $src, 'title' => $title]));
    }

    public function span(string $innerText = '', array $attrs = [], Html|Htm ...$html): self
    {
        $this->spanOpen($attrs);
        if ($innerText) {
            $this->innerText($innerText);
        }
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $this->tags[] = $tag;
            }
        }
        $this->tags[] = ['span_close' => []];
        $this->lastTag = 'span';
        return $this;
    }

    public function button(string $innerText = '', string $type = 'button', array $attrs = [], Html|Htm ...$html): self
    {
        $this->buttonOpen(array_merge($attrs, ['type' => $type]))->innerText($innerText);
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $this->tags[] = $tag;
            }
        }
        $this->buttonClose();
        $this->lastTag = 'button';
        return $this;
    }

    public function i(string $class = '', string $innerText = '', array $attrs = [], Html|Htm ...$html): self
    {
        $this->iOpen(array_merge($attrs, ['class' => $class]));
        if ($innerText) {
            $this->innerText($innerText);
        }
        foreach ($html as $item) {
            foreach ($item->getTags() as $tag) {
                $this->tags[] = $tag;
            }
        }
        $this->iClose();
        $this->lastTag = 'i';
        return $this;
    }

}