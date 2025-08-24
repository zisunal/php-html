<?php

namespace Zisunal\PhpHtml\Interfaces;

interface HtmlInterface
{
    /**
     * Initialize a new Html instance.
     * 
     * ```php
     * <?php
     * $html = new Html(); // will initialize without Html template
     * $html = new Html(['title' => 'My Page']); // will initialize with Html template having <title>My Page</title>
     * 
     * ```
     * @param array $html_template
     * Supported $html_template array keys:
     * - title: The title of the HTML document.
     * - favicon: The path to the favicon file.
     * - meta: An array of meta tags to include in the head.
     *      - Example: ['description' => 'My Page Description'].
     *          - will render <meta name="description" content="My Page Description">
     * - css_files: An array of css files: "id" as key and "path" as value to <link> tags in the head.
     *      - Example: ['main' => '/css/main.css'].
     *          - will render <link rel="stylesheet" id="main" href="/css/main.css">
     * - header_js_files: An array of JavaScript files "id" as key and "path" as value to <script> tags in the head.
     *      - Example: ['main' => '/js/main.js'].
     *          - will render <script id="main" src="/js/main.js"></script>
     * - footer_js_files: An array of JavaScript files "id" as key and "path" as value to <script> tags before the closing </body> tag.
     *      - Example: ['main' => '/js/main.js'].
     *          - will render <script id="main" src="/js/main.js"></script>
     * - inner_css: The inner CSS styles array to include in the <style> tag inside the head.
     *      - Example: ['body' => ['background-color' => 'red']].
     *          - will render <style>body { background-color: red; }</style>
     * - inner_js_header: The inner JavaScript code to include in the <script> tag inside the head.
     * - inner_js_footer: The inner JavaScript code to include in the <script> tag before the closing </body> tag.
     * - body_attrs: An array of attributes to include in the <body> tag.
     *      - Example: ['class' => 'my-body-class'].
     *          - will render <body class="my-body-class">
     * - html_attrs: An array of attributes to include in the <html> tag.
     *      - Example: ['lang' => 'en'].
     *          - will render <html lang="en">
     *
     * @param bool $minify
     */
    public function __construct(array $html_template = [], bool $minify = false);

    /**
     * Render the generated HTML tags and their contents.
     * This method should be called after all modifications to the tag have been made.
     * 
     * ```php
     * <?php
     * $html->div()->innerText('Hello World')->render();
     * ```
     * 
     * @return null|string
     */
    public function render(): null|string;

    /**
     * Create a list of HTML items.
     * 
     * ```php
     * <?php
     * $html->list(['Item 1', 'Item 2'], true);
     * ```
     * 
     * @param array $items The items to include in the list.
     * @param bool $ordered Whether to create an ordered list (ol) or unordered list (ul).
     * @param array $wrapper_attrs Attributes to include in the ul or ol.
     * @param array $item_attrs Attributes to include in each list item.
     * @return self
     */
    public function list(array $items, bool $ordered = false, array $wrapper_attrs = [], array $item_attrs = []): self;

    /**
     * Create a table of HTML items.
     * 
     * ```php
     * <?php
     * $html->table(['Header 1', 'Header 2'], [['Row 1 Col 1', 'Row 1 Col 2'], ['Row 2 Col 1', 'Row 2 Col 2']]);
     * ```
     * 
     * @param array $headers The headers for the table.
     * @param array $rows The rows of the table.
     * @param array $table_attrs Attributes to include in the <table> tag.
     * @param array $tr_attrs Attributes to include in each <tr> tag.
     * @param array $header_attrs Attributes to include in each <th> tag.
     * @param array $cell_attrs Attributes to include in each <td> tag.
     * @param bool $horizontal Whether to create a horizontal table (headers as rows).
     * @return self
     */
    public function table(array $headers, array $rows, array $table_attrs = [], array $tr_attrs = [], array $header_attrs = [], array $cell_attrs = [], bool $horizontal = false): self;

    /**
     * Create a paragraph of text.
     * 
     * ```php
     * <?php
     * $html->p('Hello World');
     * ```
     * 
     * @param string $text The text to include in the paragraph.
     * @param array $attrs Attributes to include in the <p> tag.
     * @return self
     */
    public function p(string $text, array $attrs = []): self;

    /**
     * Create a link (anchor) element.
     * 
     * ```php
     * <?php
     * $html->a('/about', 'About Us', '_blank', ['class' => 'nav-link']);
     * ```
     * 
     * @param string $href The URL the link points to.
     * @param string $text The text to display for the link.
     * @param string $target The target attribute for the link (e.g., "_blank").
     * @param array $attrs Attributes to include in the <a> tag.
     * @return self
     */
    public function a(string $href, string $text, string $target = '', array $attrs = []): self;
}
