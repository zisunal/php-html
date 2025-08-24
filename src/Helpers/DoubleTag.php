<?php

namespace Zisunal\PhpHtml\Helpers;

use Zisunal\PhpHtml\Helpers\Tag;

class DoubleTag extends Tag {
    protected static array $tags = [
        'div', 'span', 'a', 'p', 'ul', 'ol', 'li', 'table', 'tr', 'td', 'th', 'form', 'label', 
        'textarea', 'button', 'details', 'dialog', 'svg', 'canvas', 'video', 'audio', 'iframe', 
        'kbd', 'blockquote', 'nav', 'header', 'footer', 'section', 'article', 'main', 'aside', 
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'abbr', 'address', 'i', 'strong', 'small'
    ];
    protected static array $staticCallable = [
        'list', 'table', 'p', 'a', 'img', 'div', 'iframe', 'span', 'button', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i'
    ];
}