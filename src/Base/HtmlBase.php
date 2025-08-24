<?php

namespace Zisunal\PhpHtml\Base;

use Zisunal\PhpHtml\Helpers\DoubleTag;
use Zisunal\PhpHtml\Helpers\SingleTag;
use Zisunal\PhpHtml\Helpers\Tag;
use Zisunal\PhpHtml\Helpers\Helper;
use \Wongyip\HTML\Beautify;

class HtmlBase
{
    protected array $tags = [];
    protected string $lastTag = '';
    protected bool $minify = false;

    public function getTags(): array
    {
        return $this->tags;
    }

    protected function escapeHtml(string|array $text): string|array
    {
        if (is_array($text)) {
            return array_map(fn($item) => is_array($item) ? $item : htmlspecialchars($item, ENT_QUOTES | ENT_HTML5), $text);
        }
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5);
    }

    public function __call($method, $args): self
    {
        if (strpos($method, 'Open') !== false) {
            $tag_name = explode('Open', $method)[0];
            if (DoubleTag::isValid($tag_name)) {
                $this->tags[] = ["{$tag_name}_open" => $args];
                $this->lastTag = $tag_name;
            }
        } elseif (strpos($method, 'Close') !== false) {
            $tag_name = explode('Close', $method)[0];
            if (DoubleTag::isValid($tag_name)) {
                $this->tags[] = ["{$tag_name}_close" => $args];
                $this->lastTag = $tag_name;
            }
        } else if (SingleTag::isValid($method)) {
            $this->tags[] = ["{$method}" => $args];
            $this->lastTag = $method;
        } else if (in_array($method, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
            for ($i = 1; $i <= 6; $i++) {
                if ($method === "h$i") {
                    $this->{"h{$i}Open"}($args['attrs'] ?? $args[1] ?? [])->innerText($args['innerText'] ?? $args[0] ?? '');
                    if (isset($args['html']) || isset($args[2])) {
                        foreach ($args['html'] ?? [$args[2]] as $item) {
                            foreach ($item->getTags() as $tag) {
                                $this->tags[] = $tag;
                            }
                        }
                    }
                    $this->{"h{$i}Close"}();
                    $this->lastTag = "h$i";
                }
            }
        } else if (Tag::hasAlt($method)) {
            throw new \BadMethodCallException("Method $method does not exist. You can use Html::" . Tag::getAlt($method) . "() or Htm::" . Tag::getAlt($method) . "() instead.");
        } else {
            throw new \BadMethodCallException("Method $method does not exists. If you are sure that any Html tag is available with this name, then you are welcome to suggest that @ github: https://github.com/zisunal/php-html");
        }
        return $this;
    }

    public function __get($name): self
    {
        if (SingleTag::isSCValid($name)) {
            return $this->$name();
        } else if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \BadMethodCallException("Property $name does not exist in " . __CLASS__ . ". If you are sure that any Single Html tag like (br, hr, wbr) is available with this name, then you are welcome to suggest that @ github: https://github.com/zisunal/php-html");
    }

    public static function __callStatic($method, $args): self
    {
        if (Tag::isSCValid($method)) {
            return (new static())->$method(...$args);
        } else if (Tag::hasAlt($method)) {
            throw new \BadMethodCallException("Static method $method does not exist. You can use Html::" . Tag::getAlt($method) . "() instead.");
        }
        throw new \BadMethodCallException("Static method $method does not exist in " . __CLASS__ . ". If you are sure that any Html tag is available with this name, then you are welcome to suggest that @ github: https://github.com/zisunal/php-html");
    }

    public function innerText(string $text): self
    {
        if (DoubleTag::isValid($this->lastTag)) {
            $text = $this->escapeHtml($text);
            $this->tags[count($this->tags) - 1]["{$this->lastTag}_open"]['innerText'] = $text;
        }
        return $this;
    }

    public function minify(bool $minify = true): self
    {
        $this->minify = $minify;
        return $this;
    }

    public function render(bool $return = false): null|string
    {
        $html_str = '';
        if (isset($this->html_template) && is_array($this->html_template) && !empty($this->html_template)) {
            $html_str .= Helper::openHtml((array) $this->html_template, $this->minify);
        }
        foreach ($this->tags as $tag) {
            foreach($tag as $tag_name => $attrs) {
                if (strpos($tag_name, '_') !== false) {
                    $tag_arr = explode('_', $tag_name);
                    $tag_name = $tag_arr[0];
                    $tag_state = $tag_arr[1];
                }
                if (DoubleTag::isValid($tag_name)) {
                    $html_str .= $this->renderDoubleTag($tag_state, $tag_name, $attrs);
                } elseif (SingleTag::isValid($tag_name)) {
                    $html_str .= "<$tag_name" . $this->buildAttrs($attrs) . " />\n";
                } else {
                    throw new \Exception("Invalid tag: $tag_name");
                }
            }
        }
        if (isset($this->html_template) && is_array($this->html_template) && !empty($this->html_template)) {
            $html_str .= Helper::closeHtml(
                $this->html_template['footer_js_files'] ?? [], 
                $this->html_template['js_integrity'] ?? [], 
                $this->html_template['js_crossorigin'] ?? [], 
                $this->html_template['js_type'] ?? [], 
                $this->html_template['inner_js_footer'] ?? null, 
                $this->minify
            );
        }
        if ($return) {
            return $html_str;
        }
        echo $this->minify ? $html_str : Beautify::html($html_str);
        return null;
    }

    protected function buildAttrs(array $attrs): string
    {
        $result = '';
        foreach ($attrs as $name => $value) {
            if ($name === 'innerText') {
                continue;
            }
            if (is_array($value)) {
                foreach ($value as $item_name => $item) {
                    $result .= " $item_name=\"" . $this->escapeHtml($item) . "\"";
                }
            } else {
                $result .= " $name=\"" . $this->escapeHtml($value) . "\"";
            }
        }
        return $result;
    }

    protected function transpose(array $array): array
    {
        $transposed = [];
        foreach ($array as $row) {
            foreach ($row as $key => $value) {
                $transposed[$key][] = $value;
            }
        }
        return $transposed;
    }

    protected function renderDoubleTag(string $tag_state, string $tag_name, array $attrs): string
    {
        $html_str = '';
        if ($tag_state === 'open') {
            $html_str .= "<$tag_name" . $this->buildAttrs($attrs) . ">";
            if (isset($attrs['innerText'])) {
                $html_str .= $this->escapeHtml($attrs['innerText']) . "";
            }
        } else {
            $html_str .= "</$tag_name>";
        }
        return $html_str;
    }

    protected function isSingleTag(string $tag_name): bool
    {
        return SingleTag::isValid($tag_name);
    }

    protected function isDoubleTag(string $tag_name): bool
    {
        return DoubleTag::isValid($tag_name);
    }
}