[️⬅️Back to README](../readme.md)
---
## `Html` Class
    This is the main class for generating HTML content using a more intuitive syntax. It provides a fluent interface for building HTML elements and supports both static and dynamic content generation.
### You need to initialize the `Html` class before using it:
```php
use Zisunal\PhpHtml\Html;

$html = new Html();
```
*Remember to call the `render()` method at the very end to render the HTML output.*
### Templating Methods:
---
    Adding any of the below methods will automatically include the whole Html document structure.
    
| Method Name | Description | Arguments | Example |
|-------------|-------------|-----------|---------|
| `title()`   | Sets the title of the HTML document. | `string $title` | `$html->title('My Page');` |
| `favicon()` | Sets the favicon for the HTML document. | `string $source` | `$html->favicon('source/of/favicon.ico');` |
| `removeFavicon()` | Removes the favicon from the HTML document. | X | `$html->removeFavicon();` |
| `addCssFile()` | Adds a CSS file to the document head. | <ul><li>`string $source`</li><li>`string\|null $id` => Optional, Default: `null`</li><li>`string\|null $rel` => Optional, Default: `null`</li><li>`string\|null $integrity` => Optional, Default: `null`</li><li>`bool\|string $crossorigin` => Optional, Default: `false`</li></ul> | `$html->addCssFile('path/to/style.css');` |
| `removeCssFile()` | Removes a CSS file from the document head. | `string $id` | `$html->removeCssFile('style.css');` |
| `addMeta()` | Adds a meta tag to the document head. Meta `Viewport` is already included, no need to add separately | `string $name`, `string $content` | `$html->addMeta('description', 'This is my page description.');` |
| `meta()` | Adds any custom meta tag to the document head. | `array $attrs` | `$html->meta(['name' => 'keywords', 'content' => 'php,html']);` |
| `removeMeta()` | Removes a meta tag from the document head. | `string $name` | `$html->removeMeta('description');` |
| `updateMeta()` | Updates an existing meta tag in the document head. | `string $name`, `string $content` | `$html->updateMeta('description', 'New description content.');` |
| `addJsFile()` | Adds a JavaScript file to the document. | <ul><li>`string $source`</li><li>`string\|null $id` => Optional, Default: `null`</li><li>`string\|null $integrity` => Optional, Default: `null`</li><li>`string\|null $type` => Optional, Default: `null`</li><li>`bool $header` => "Whether to load into the `head` tag" Optional, Default: `false`</li><li>`bool $crossorigin` => Optional, Default: `false`</li></ul> | `$html->addJsFile('path/to/script.js');` |
| `removeJsFile()` | Removes a JavaScript file from the document. | `string $id` | `$html->removeJsFile('script.js');` |
| `css()` | Adds a CSS style block to the document. | `string $selector`, `array $styles` | `$html->css('body', [`</br>`'background-color' => 'red'`</br>`]);` |
| `js()` | Adds a JavaScript code block to the document. | <ul><li>`string $code`</li><li>`bool $header`=> "Whether to load into the <head> tag" Optional, Default: `false`</li></ul> | `$html->js('console.log("Hello, World!");');` |
| `htmlAttrs()` | Adds HTML attributes to the document. | `array $attrs` | `$html->htmlAttrs(['lang' => 'en', 'dir' => 'ltr']);` |
| `bodyAttrs()` | Adds body attributes to the document. | `array $attrs` | `$html->bodyAttrs(['class' => 'my-class']);` |
---
### All `Double Tags` methods:
---
    These methods allow you to easily create HTML elements that have both opening and closing tags. You can customize as needed by passing attributes as an array or using inner text methods.
---
- List of supported `Double Tags`:
  - All of the tags below have 2 methods: `tagnameOpen()` and `tagnameClose()`
    - 
    `div`, `span`, `a`, `p`, `ul`, `ol`, `li`, `table`, `tr`, `td`, `th`, `form`, `label`, `textarea`, `button`, `details`, `dialog`, `svg`, `canvas`, `video`, `audio`, `iframe`, `kbd`, `blockquote`, `nav`, `header`, `footer`, `section`, `article`, `main`, `aside`, `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `abbr`, `address`, `i`, `strong`, `small`
    -
    - Example:
    ```php
    $html->
    divOpen(['class' => 'container'])
        ->h1Open()
            ->innerText('Hello World')
        ->h1Close()
        ->hr()
        ->pOpen()
            ->innerText('This is a static paragraph.')
        ->pClose()
    ->divClose();
    ```
### Special `Double Tags` methods:
---
    These special methods allow you to easily create Double HTML tags without the need to manually use the `Open` and `Close` methods.
| Method Name | Description | Arguments | Example |
|-------------|-------------|-----------|---------|
| `list()` | Generates a list (`<ul>` or `<ol>`) with the provided items. | <ul><li>`array $items` => List items</li><li>`bool $ordered` => Whether to create an ordered list (`<ol>`) or unordered list (`<ul>`), Optional, Default: `<ul>` `false`</li><li>`array $wrapper_attrs` => Attributes for the list wrapper `<ul>`, Optional, Default: `[]`</li><li>`array $item_attrs` => Attributes for each list item `<li>`, Optional, Default: `[]`</li></ul> | `$html->list(['Item 1', 'Item 2']);` |
| `table()` | Generates a table with the provided headers and rows. | <ul><li>`array $headers` => Table headers</li><li>`array $rows` => Table rows</li><li>`array $table_attrs` => Attributes for the table, Optional, Default: `[]`</li><li>`array $tr_attrs` => Attributes for each row `<tr>`, Optional, Default: `[]`</li><li>`array $header_attrs` => Attributes for each header cell `<th>`, Optional, Default: `[]`</li><li>`array $cell_attrs` => Attributes for each data cell `<td>`, Optional, Default: `[]`</li><li>`bool $horizontal` => Whether to create a horizontal table, Optional, Default: `false`</li></ul> | `$html->table(['Header 1', 'Header 2'], [['Row 1 Col 1', 'Row 1 Col 2'], ['Row 2 Col 1', 'Row 2 Col 2']]);` |
| `p()` | Generates a paragraph (`<p>`) element. | <ul><li>`string $paragraph` => The paragraph text</li><li>`array $attrs` => Optional attributes for the paragraph</li></ul> | `$html->p('This is a paragraph.');` |
| `h1()` | Generates a heading (`<h1>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h1('This is a heading.');` |
| `h2()` | Generates a heading (`<h2>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h2('This is a subheading.');` |
| `h3()` | Generates a heading (`<h3>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h3('This is a sub-subheading.');` |
| `h4()` | Generates a heading (`<h4>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h4('This is a sub-sub-subheading.');` |
| `h5()` | Generates a heading (`<h5>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h5('This is a sub-sub-sub-subheading.');` |
| `h6()` | Generates a heading (`<h6>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `$html->h6('This is a sub-sub-sub-sub-subheading.');` |
| `a()` | Generates an anchor (`<a>`) element. | <ul><li>`string $href` => The link URL</li><li>`string $text` => The link text</li><li>`string $target` => Optional target attribute (e.g., `_blank`)</li><li>`array $attrs` => Optional attributes for the anchor</li></ul> | `$html->a('https://example.com', 'Visit Example', '_blank');` |
| `div()` | Generates a `<div>` element. | <ul><li>`array $attrs` => Optional attributes for the div</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `$html->div(['class' => 'my-class']);` |
| `span()` | Generates a `<span>` element. | <ul><li>`string $innerText` => The text content for the span</li><li>`array $attrs` => Optional attributes for the span</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `$html->span('This is a span.');` |
| `button()` | Generates a `<button>` element. | <ul><li>`string $innerText` => The button text</li><li>`string $type` => The button type (e.g., `button`, `submit`, `reset`), Default: `button`</li><li>`array $attrs` => Optional attributes for the button</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `$html->button('Click Me', 'button', ['class' => 'btn']);` |
| `i()` | Generates an `<i>` element. | <ul><li>`string $class` => Optional class attribute for the `<i>` element</li><li>`string $innerText` => Optional text content for the `<i>` element</li><li>`array $attrs` => Optional attributes for the `<i>` element</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `$html->i('fa fa-icon', 'Icon', ['class' => 'icon-class']);` |
---
### All `Single Tags` Methods
---
    These methods allow you to easily create HTML elements that have no closing tag. You can customize as needed by passing attributes as an array.
---
- List of supported `Single Tags`:
  - All of the tags below have 1 method: `tagname()`
    - 
    `br`, `img`, `input`, `source`, `track`, `wbr`, `hr`, `iframe`
    -
    All of these methods take an optional array of attributes as their only argument.
    - Example:
    ```php
    $html->br();
    $html->img(['src' => 'image.jpg', 'alt' => 'Image']);
    $html->input(['type' => 'text', 'name' => 'username']);
    $html->source(['src' => 'video.mp4', 'type' => 'video/mp4']);
    $html->track(['src' => 'subtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English']);
    $html->wbr();
    $html->hr();
    $html->meta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
    $html->iframe(['src' => 'https://www.example.com', 'width' => '600', 'height' => '400']);
    ```
---
### Some Special `Single Tags` can take their custom arguments also:
| Method Name | Description | Arguments | Example |
|-------------|-------------|-----------|---------|
| `img()` | Generates an `<img>` element. | <ul><li>`string $src` => The source URL of the image</li><li>`string $alt` => Optional, Default: `''`, The alt text for the image</li><li>`array $attrs` => Optional attributes for the image</li></ul> | `$html->img('path/to/image.jpg', 'An example image', ['class' => 'my-image']);` |
| `iframe()` | Generates an `<iframe>` element. | <ul><li>`string $src` => The source URL of the iframe</li><li>`string $title` => Optional, Default: `''`, The title for the iframe</li><li>`array $attrs` => Optional attributes for the iframe</li></ul> | `$html->iframe('https://www.example.com', 'Example Site', ['width' => '600', 'height' => '400']);` |
---
### Last but not least
    These methods can be called statically:
    - Html::hr()
    - Html::br()
    - Html::wbr()
---
### Finally call the `render()` method to print the final HTML output
***OR*** 
### `render(true)` to return the HTML as a string