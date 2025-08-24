<?php

namespace Zisunal\PhpHtml\Helpers;

use Zisunal\PhpHtml\Base\HtmlBase;
use Zisunal\PhpHtml\Helpers\DoubleTag;
use Zisunal\PhpHtml\Helpers\SingleTag;
use MatthiasMullie\Minify;

class Helper
{
    public static function updateBase(): bool
    {
        try {
            $double_tags = array_map(fn($tag) => "{$tag}Open", DoubleTag::all());
            $double_tags = array_merge($double_tags, array_map(fn($tag) => "{$tag}Close", DoubleTag::all()));
            $single_tags = SingleTag::all();

            $tags = array_merge($double_tags, $single_tags);
            $new_methods = [];
            $base_methods = get_class_methods(HtmlBase::class);
            foreach ($tags as $tag) {
                if (!in_array($tag, $base_methods)) {
                    $new_methods[] = self::buildMethod($tag);
                }
            }
            if (!empty($new_methods)) {
                $base_class = file_get_contents(__DIR__ . '/../Base/HtmlBase.php');
                $base_class = str_replace('// Auto-generated methods', implode("\n", $new_methods) . "\n// Auto-generated methods", $base_class);
                file_put_contents(__DIR__ . '/../Base/HtmlBase.php', $base_class);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function buildMethod(string $tag): string
    {
        if (DoubleTag::isValid($tag)) {
        return <<<PHP
    /**
     * This method opens a html $tag tag
     * 
     * ```php
     * <?php
     * \$html->{$tag}Open(['class' => 'container']);
     * ```
     * 
     * @param array[] \$args
     * @return Html
     */
    public function {$tag}Open(array ...\$args): Html
    {
        \$this->tags[] = ['{$tag}_open' => \$args];
        \$this->lastTag = '$tag';
        return \$this;
    }
    /**
     * This method closes a html $tag tag
     * 
     * ```php
     * <?php
     * \$html->{$tag}Close();
     * ```
     * @return Html
     */
    public function {$tag}Close(): Html
    {
        \$this->tags[] = ['{$tag}_close' => []];
        \$this->lastTag = '$tag';
        return \$this;
    }
PHP;
        } else {
            return <<<PHP
    /**
     * This method creates a html $tag tag
     * 
     * ```php
     * <?php
     * \$html->$tag(['class' => 'container']);
     * ```
     * @param array[] \$args
     * @return Html
     */
    public function $tag(array ...\$args): Html
    {
        \$this->tags[] = ['{$tag}' => \$args];
        \$this->lastTag = '$tag';
        return \$this;
    }
PHP;
        }
    }

    public static function openHtml(array $data, $minify = false): string
    {
        $html_str = "<!DOCTYPE html><html";
        if (isset($data['html_attrs']) && is_array($data['html_attrs']) && !empty($data['html_attrs'])) {
            foreach (array_unique($data['html_attrs']) as $name => $value) {
                $html_str .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . "\"";
            }
        }
        $html_str .= "><head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        if (isset($data['title'])) {
            $html_str .= "<title>{$data['title']}</title>";
        }
        if (isset($data['favicon'])) {
            $html_str .= "<link rel=\"icon\" type=\"image/x-icon\" href=\"{$data['favicon']}\">";
        }
        if (isset($data['meta']) && is_array($data['meta'])) {
            if (self::is_assoc($data['meta'])) {
                foreach ($data['meta'] as $name => $content) {
                    $html_str .= "<meta name=\"" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "\" content=\"" . htmlspecialchars($content, ENT_QUOTES | ENT_HTML5) . "\">";
                }
            } else {
                foreach ($data['meta'] as $meta) {
                    if (is_array($meta) && isset($meta['name'], $meta['content'])) {
                        $html_str .= "<meta name=\"" . htmlspecialchars($meta['name'], ENT_QUOTES | ENT_HTML5) . "\" content=\"" . htmlspecialchars($meta['content'], ENT_QUOTES | ENT_HTML5) . "\">";
                    } else {
                        $html_str .= "<meta name=\"" . htmlspecialchars($meta, ENT_QUOTES | ENT_HTML5) . "\" content=\"" . htmlspecialchars($meta, ENT_QUOTES | ENT_HTML5) . "\">";
                    }
                }
            }
        }
        if (isset($data['css_files']) && is_array($data['css_files'])) {
            foreach (array_unique($data['css_files']) as $id => $path) {
                $html_str .= "<link";
                if (isset($data['css_rel'][$id])) {
                    $html_str .= " rel=\"" . htmlspecialchars($data['css_rel'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                } else {
                    $html_str .= " rel=\"stylesheet\"";
                }
                $html_str .= " id=\"$id\" href=\"$path\"";
                if (isset($data['css_integrity'][$id])) {
                    $html_str .= " integrity=\"" . htmlspecialchars($data['css_integrity'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                }
                if (isset($data['css_crossorigin'][$id])) {
                    if (is_string($data['css_crossorigin'][$id])) {
                        $html_str .= " crossorigin=\"" . htmlspecialchars($data['css_crossorigin'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                    } else if ($data['css_crossorigin'][$id] === true) {
                        $html_str .= " crossorigin";
                    }
                }
                $html_str .= ">";
            }
        }
        if (isset($data['inner_css'])) {
            if (self::is_assoc($data['inner_css'])) {
                $css = '';
                foreach ($data['inner_css'] as $selector => $styles) {
                    $css .= "$selector {";
                    foreach ($styles as $property => $value) {
                        $css .= "$property: $value;";
                    }
                    $css .= "}";
                }
            } else {
                $css = $data['inner_css'];
            }
            if (!$minify) {
                $csstidy = new \csstidy();
                $csstidy->set_cfg('optimise_shorthands', 4);
                $csstidy->set_cfg('template', 'low');
                $csstidy->parse($css);
                $css = $csstidy->print->plain();
            } else {
                $css = (new Minify\CSS())->add($css)->minify();
            }
            $html_str .= "<style>{$css}</style>";
        }
        if (isset($data['header_js_files'])) {
            foreach (array_unique($data['header_js_files']) as $id => $path) {
                $html_str .= "<script id=\"$id\" src=\"$path\"";
                if (isset($data['js_integrity'][$id])) {
                    $html_str .= " integrity=\"" . htmlspecialchars($data['js_integrity'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                }
                if (isset($data['js_crossorigin'][$id])) {
                    if (is_string($data['js_crossorigin'][$id])) {
                        $html_str .= " crossorigin=\"" . htmlspecialchars($data['js_crossorigin'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                    } else if ($data['js_crossorigin'][$id] === true) {
                        $html_str .= " crossorigin";
                    }
                }
                if (isset($data['js_type'][$id])) {
                    $html_str .= " type=\"" . htmlspecialchars($data['js_type'][$id], ENT_QUOTES | ENT_HTML5) . "\"";
                }
                $html_str .= "></script>";
            }
        }
        if (isset($data['inner_js_header'])) {
            $js = $data['inner_js_header'];
            if ($minify) {
                $js = (new Minify\JS())->add($js)->minify();
            }
            $html_str .= "<script>{$js}</script>";
        }
        $html_str .= "</head><body";
        if (isset($data['body_attrs']) && is_array($data['body_attrs'])) {
            foreach (array_unique($data['body_attrs']) as $name => $value) {
                $html_str .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . "\"";
            }
        }
        $html_str .= ">";
        return $html_str;
    }

    public static function closeHtml(array $footer_js_files = [], array $js_integrity = [], array $js_crossorigin = [], array $js_type = [], string|null $inner_js_footer = '', bool $minify = false): string
    {
        $html_str = '';
        if (!empty($footer_js_files)) {
            foreach (array_unique($footer_js_files) as $id => $path) {
                $html_str .= "<script id=\"$id\" src=\"$path\"";
                if (isset($js_integrity[$id])) {
                    $html_str .= " integrity=\"" . htmlspecialchars($js_integrity[$id], ENT_QUOTES | ENT_HTML5) . "\"";
                }
                if (isset($js_crossorigin[$id])) {
                    if (is_string($js_crossorigin[$id])) {
                        $html_str .= " crossorigin=\"" . htmlspecialchars($js_crossorigin[$id], ENT_QUOTES | ENT_HTML5) . "\"";
                    } else if ($js_crossorigin[$id] === true) {
                        $html_str .= " crossorigin";
                    }
                }
                if (isset($js_type[$id])) {
                    $html_str .= " type=\"" . htmlspecialchars($js_type[$id], ENT_QUOTES | ENT_HTML5) . "\"";
                }
                $html_str .= "></script>";
            }
        }
        if (!empty($inner_js_footer)) {
            if ($minify) {
                $inner_js_footer = (new Minify\JS())->add($inner_js_footer)->minify();
            }
            $html_str .= "<script>$inner_js_footer</script>";
        }
        $html_str .= "</body></html>";
        return $html_str;
    }

    public static function is_assoc(array $array): bool
    {
        if (empty($array)) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
