<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\HTML;

use DOMDocument;
use DOMXPath;
use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Toolbox\Str;

final class Html
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function bolderHtmlString($string, $type = 1){
        return '<strong>'. Str::invoke()->makeItReadable($string, $type) .'</strong>';
    }

    public function parseHTMLTags($tags, $enclose = true, $trim = false) {

        // if empty
        if (Validators::invoke()->isEmpty($tags)){
            return '';
        }

        $out = '';
        if(!is_array($tags)){
            // decode entities
            $tags = html_entity_decode($tags);
            // remove opening tag
            $tags = str_replace('<', '', $tags);
            // remove closing tag
            $tags = str_replace('>', ',', $tags);

            // convert to array
            $tags = explode(',', $tags);
        }

        foreach ($tags as $item){
            if(!is_array($item)){
                // decode entities
                $item = html_entity_decode($item);
                // remove opening tag
                $item = str_replace('<', '', $item);
                // remove closing tag
                $item = str_replace('>', ',', $item);
                // remove spaces
                $item = str_replace(' ', '', $item);
                // remove special characters
                $item = preg_replace('/[^A-Za-z0-9\-]/', '', $item);
                // get prepared tags
                $out .=  true === $enclose ? "<$item>" : (count($tags) > 1 ? "$item," : $item);
            }
        }
        $out = ((true === $trim) ? rtrim("$out,", ',') : $out);
        return !empty($out) ? $out : '';
    }

    public function nl2br($string)
    {
        return str_replace("\n", '<br />', $string);
    }

    public function formatTags($string, $splitter = ',', $notWanted = null){
        $notWanted = empty($notWanted) ? '\\/:*?"<>;,|' : $notWanted;
        return preg_replace('{(.)\1+}','$1', str_replace(str_split($notWanted), $splitter, $string));
    }

    public function bolderSprintf($argument, $wrapper){
        $argument = '<strong>'. ucwords(strtolower(str_replace('_', ' ', $argument))) .'</strong>';
        return sprintf($wrapper, $argument);
    }

    public function oddEvenClass(int $number){
        return strtolower(Validators::invoke()->isNumberOdd($number) ? 'Even' : 'Odd');
    }

    public function progressbar($done, $total, $info = "", $width = 50) {
        $percentage = round(($done * 100) / $total);
        $bar        = round(($width * $percentage) / 100);
        return sprintf("%s%%[%s>%s]%s\r", $percentage, str_repeat("=", (int)$bar), str_repeat(" ", $width-$bar), $info);
    }

    public function svgIcon($path, $iconClass = "", $svgClass = "")
    {

        if ( ! file_exists($path)) {
            return "<!-- SVG file not found: ".$path." -->";
        }

        $svg_content = file_get_contents($path);

        $dom = new DOMDocument();
        $dom->loadXML($svg_content);

        // remove unwanted comments
        $xpath = new DOMXPath($dom);
        foreach ($xpath->query("//comment()") as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        // add class to svg
        if ( ! empty($svgClass)) {
            foreach ($dom->getElementsByTagName("svg") as $element) {
                $element->setAttribute("class", $svgClass);
            }
        }

        // remove unwanted tags
        $title = $dom->getElementsByTagName("title");
        if ($title["length"]) {
            $dom->documentElement->removeChild($title[0]);
        }

        $desc = $dom->getElementsByTagName("desc");
        if ($desc["length"]) {
            $dom->documentElement->removeChild($desc[0]);
        }

        $defs = $dom->getElementsByTagName("defs");
        if ($defs["length"]) {
            $dom->documentElement->removeChild($defs[0]);
        }

        // remove unwanted id attribute in g tag
        $g =  $dom->getElementsByTagName("g");
        foreach ($g as $el) {
            $el->removeAttribute("id");
        }

        $mask =  $dom->getElementsByTagName("mask");
        foreach ($mask as $el) {
            $el->removeAttribute("id");
        }

        $rect =  $dom->getElementsByTagName("rect");
        foreach ($rect as $el) {
            $el->removeAttribute("id");
        }

        $path =  $dom->getElementsByTagName("path");
        foreach ($path as $el) {
            $el->removeAttribute("id");
        }

        $circle =  $dom->getElementsByTagName("circle");
        foreach ($circle as $el) {
            $el->removeAttribute("id");
        }

        $use =  $dom->getElementsByTagName("use");
        foreach ($use as $el) {
            $el->removeAttribute("id");
        }

        $polygon =  $dom->getElementsByTagName("polygon");
        foreach ($polygon as $el) {
            $el->removeAttribute("id");
        }

        $ellipse =  $dom->getElementsByTagName("ellipse");
        foreach ($ellipse as $el) {
            $el->removeAttribute("id");
        }

        $string = $dom->saveXML($dom->documentElement);

        // remove empty lines
        $string = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);

        $cls    = ["svg-icon"];

        if ( ! empty($iconClass)) {
            $cls = array_merge($cls, explode(" ", $iconClass));
        }

        return "<span class=". implode(" ", $cls) .">" . $string . "</span>";
    }

    public function svgIconFromFile(string $request, string $filePath, ?string $class = null)
    {
        $class = $class ?? '';
        $ref   = $filePath .'#' .$request;
        $html  = "
                <svg class=\"{$class}\" viewBox=\"0 0 100 100\">
                    <use xlink:href=\"{$ref}\" />
                </svg>
        ";

        return !empty($filePath) && !empty($request) ? $html : null;
    }

    /**
     * Strip style markup from text.
     *
     * @param string $markup
     * @param array  $allowed
     *
     * @return string
     */
    public function cleanHtml($markup, $allowed = ['<p>','<span>','<b>','<strong>',
        '<i>','<em>', '<br>','<a>','<cite>','<blockquote>','<cite>','<ul>','<ol>',
        '<li>','<dl>', '<dt>','<dd>','<h2>','<h3>','<h4>','<h5>','<h6>']): string
    {
        $tags = '';

        // Add uppercase and lowercase of all allowed tags as a string.
        foreach ($allowed as $tag) {

            $tags .= strtolower($tag) . strtoupper($tag);
        }
        // Remove all but allowed tags.
        $stripped = strip_tags($markup, $tags);
        // Remove class and style attributes (double quoted).
        $stripped = preg_replace('/(<[^>]+) (style|class)=".*?"/i', '$1', $stripped);
        // Remove class and style attributes (single quoted).
        $stripped = preg_replace('/(<[^>]+) (style|class)=\'.*?\'/i', '$1', $stripped);
        // Remove specific (unquoted) class "MsoNormal".
        $stripped = preg_replace('/\s?\bclass=MsoNormal\b\s?/i', '$1', $stripped);
        // Remove empty </p> and </span> tags.
        $stripped = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/i', '', $stripped);

        return preg_replace('/<span[^>]*>[\s|&nbsp;]*<\/span>/i', '', $stripped);
    }

    /**
     * Get the url from a "href" attribute of an anchor tag in a given string of HTML markup.
     *   If the given markup contains multiple hrefs the first match is returned.
     *
     * @param string $markup
     *
     * @return string
     */
    public function urlFromHtml($markup): bool|string
    {
        $string = $this->cleanHtml(trim($markup), ['<a>']);
        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $string, $links);
        if (is_array($links) && isset($links['href']) && !empty($links['href'])) {
            // The first found url.
            return reset($links['href']);
        } else {
            return false;
        }
    }

    public function tagCloud($str, $minFontSize = 12, $maxFontSize = 30)
    {

        // Store frequency of words in an array
        $frequency = [];

        // Get individual words and build a frequency table
        foreach (str_word_count($str, 1) as $word) {
            // For each word found in the frequency table, increment its value by one
            array_key_exists($word, $frequency) ? $frequency[$word]++ : $frequency[$word] = 0;
        }

        $minimumCount = min(array_values($frequency));
        $maximumCount = max(array_values($frequency));
        $spread       = $maximumCount - $minimumCount;
        $tags         = [];

        $spread == 0 && $spread = 1;

        foreach ($frequency as $tag => $count) {
            $fontSize = $minFontSize + ($count - $minimumCount) * ($maxFontSize - $minFontSize) / $spread;
            $tags[]   = [
                'count' => floor($count),
                'size'  => floor($fontSize),
                'tag'   => htmlspecialchars(stripslashes($tag)),
            ];
        }

        return $tags;
    }

    public function html2Text(): Html2Text
    {
        return Html2Text::invoke();
    }

    public function htmlCleaner(): HtmlCleaner
    {
        return HtmlCleaner::invoke();
    }
}