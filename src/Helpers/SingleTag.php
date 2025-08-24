<?php

namespace Zisunal\PhpHtml\Helpers;

use Zisunal\PhpHtml\Helpers\Tag;

class SingleTag extends Tag {
    protected static array $tags = [
        'br', 'img', 'input', 'source', 'track', 'wbr', 'hr', 'meta', 'iframe'
    ];
    protected static array $staticCallable = [
        'br', 'hr', 'wbr'
    ];
}