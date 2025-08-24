[️⬅️Back to README](../readme.md)
---
## `Htm` Class
    This is class for generating HTML content using static methods. You don't need to initialize it to use any of its methods.
### You can use the `Htm` class directly:
```php
use Zisunal\PhpHtml\Htm;

Htm::div()->render();
```
*Calling the `render()` method is not required if you are just passing the generated HTML to any `Html` method.*
### Templating Methods:
---
`Htm` does not automatically include the whole HTML document structure. So, it doesn't have any Templating methods.
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
    Htm::divOpen(['class' => 'container'])
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
| `list()` | Generates a list (`<ul>` or `<ol>`) with the provided items. | <ul><li>`array $items` => List items</li><li>`bool $ordered` => Whether to create an ordered list (`<ol>`) or unordered list (`<ul>`), Optional, Default: `<ul>` `false`</li><li>`array $wrapper_attrs` => Attributes for the list wrapper `<ul>`, Optional, Default: `[]`</li><li>`array $item_attrs` => Attributes for each list item `<li>`, Optional, Default: `[]`</li></ul> | `Htm::list(['Item 1', 'Item 2']);` |
| `table()` | Generates a table with the provided headers and rows. | <ul><li>`array $headers` => Table headers</li><li>`array $rows` => Table rows</li><li>`array $table_attrs` => Attributes for the table, Optional, Default: `[]`</li><li>`array $tr_attrs` => Attributes for each row `<tr>`, Optional, Default: `[]`</li><li>`array $header_attrs` => Attributes for each header cell `<th>`, Optional, Default: `[]`</li><li>`array $cell_attrs` => Attributes for each data cell `<td>`, Optional, Default: `[]`</li><li>`bool $horizontal` => Whether to create a horizontal table, Optional, Default: `false`</li></ul> | `Htm::table(['Header 1', 'Header 2'], [['Row 1 Col 1', 'Row 1 Col 2'], ['Row 2 Col 1', 'Row 2 Col 2']]);` |
| `p()` | Generates a paragraph (`<p>`) element. | <ul><li>`string $paragraph` => The paragraph text</li><li>`array $attrs` => Optional attributes for the paragraph</li></ul> | `Htm::p('This is a paragraph.');` |
| `h1()` | Generates a heading (`<h1>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h1('This is a heading.');` |
| `h2()` | Generates a heading (`<h2>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h2('This is a subheading.');` |
| `h3()` | Generates a heading (`<h3>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h3('This is a sub-subheading.');` |
| `h4()` | Generates a heading (`<h4>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h4('This is a sub-sub-subheading.');` |
| `h5()` | Generates a heading (`<h5>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h5('This is a sub-sub-sub-subheading.');` |
| `h6()` | Generates a heading (`<h6>`) element. | <ul><li>`string $heading` => The heading text</li><li>`array $attrs` => Optional attributes for the heading</li></ul> | `Htm::h6('This is a sub-sub-sub-sub-subheading.');` |
| `a()` | Generates an anchor (`<a>`) element. | <ul><li>`string $href` => The link URL</li><li>`string $text` => The link text</li><li>`string $target` => Optional target attribute (e.g., `_blank`)</li><li>`array $attrs` => Optional attributes for the anchor</li></ul> | `Htm::a('https://example.com', 'Visit Example', '_blank');` |
| `div()` | Generates a `<div>` element. | <ul><li>`array $attrs` => Optional attributes for the div</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `Htm::div(['class' => 'my-class']);` |
| `span()` | Generates a `<span>` element. | <ul><li>`string $innerText` => The text content for the span</li><li>`array $attrs` => Optional attributes for the span</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `Htm::span('This is a span.');` |
| `button()` | Generates a `<button>` element. | <ul><li>`string $innerText` => The button text</li><li>`string $type` => The button type (e.g., `button`, `submit`, `reset`), Default: `button`</li><li>`array $attrs` => Optional attributes for the button</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `Htm::button('Click Me', 'button', ['class' => 'btn']);` |
| `i()` | Generates an `<i>` element. | <ul><li>`string $class` => Optional class attribute for the `<i>` element</li><li>`string $innerText` => Optional text content for the `<i>` element</li><li>`array $attrs` => Optional attributes for the `<i>` element</li><li>`Html` / `Htm` ...$html => Any number of Html contents generated by `Html` or `Htm` classes</li></ul> | `Htm::i('fa fa-icon', 'Icon', ['class' => 'icon-class']);` |
---
### All `Single Tags` Methods
---
    These methods allow you to easily create HTML elements that have no closing tag. You can customize as needed by passing attributes as an array.
---
- List of supported `Single Tags`:
  - All of the tags below have 1 method: `tagname()`
    - 
    `br`, `img`, `input`, `source`, `track`, `wbr`, `hr`, `meta`, `iframe`
    -
    All of these methods take an optional array of attributes as their only argument.
    - Example:
    ```php
    Htm::br();
    Htm::img(['src' => 'image.jpg', 'alt' => 'Image']);
    Htm::input(['type' => 'text', 'name' => 'username']);
    Htm::source(['src' => 'video.mp4', 'type' => 'video/mp4']);
    Htm::track(['src' => 'subtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English']);
    Htm::wbr();
    Htm::hr();
    Htm::meta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
    Htm::iframe(['src' => 'https://www.example.com', 'width' => '600', 'height' => '400']);
    ```
---
### Some Special `Single Tags` can take their custom arguments also:
| Method Name | Description | Arguments | Example |
|-------------|-------------|-----------|---------|
| `img()` | Generates an `<img>` element. | <ul><li>`string $src` => The source URL of the image</li><li>`string $alt` => Optional, Default: `''`, The alt text for the image</li><li>`array $attrs` => Optional attributes for the image</li></ul> | `Htm::img('path/to/image.jpg', 'An example image', ['class' => 'my-image']);` |
| `iframe()` | Generates an `<iframe>` element. | <ul><li>`string $src` => The source URL of the iframe</li><li>`string $title` => Optional, Default: `''`, The title for the iframe</li><li>`array $attrs` => Optional attributes for the iframe</li></ul> | `Htm::iframe('https://www.example.com', 'Example Site', ['width' => '600', 'height' => '400']);` |

### Finally call the `render()` method to print the final HTML output
***OR*** 
### `render(true)` to return the HTML as a string
***OR***
### Just pass the Htm inside any Html's supported methods as per [Html Docs](./html.md)