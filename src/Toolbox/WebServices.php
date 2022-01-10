<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

class WebServices
{
    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }


    /**
     * Get a Website favicon image
     * @param  string $url website url
     * @param  array $attributes Optional, additional key/value attributes to include in the IMG tag
     * @return string containing complete image tag
     */
    public function getFavicon($url, $attributes = array()) {
        if (self::ishttps()):
            $protocol = 'https://';
        else:
            $protocol = 'http://';
        endif;
        $apiUrl = $protocol . 'www.google.com/s2/favicons?domain=';
        $attr = "";
        if (isset($attributes) and is_array($attributes) and !empty($attributes)):
            foreach ($attributes as $attributeName => $attributeValue):
                $attr.= $attributeName . '="' . $attributeValue . '" ';
            endforeach;
        endif;
        if (strpos($url, "http") !== false):
            $url = str_replace('http://', "", $url);
        endif;
        return '<img src="' . $apiUrl . $url . '" ' . trim($attr) . ' />';
    }

    /**
     * Get a QR code
     * @param  string  $string String to generate QR code for.
     * @param  integer $width QR code width
     * @param  integer $height QR code height
     * @param  array $attributes Optional, additional key/value attributes to include in the IMG tag
     * @return string containing complete image tag
     */
    public function getQRcode($string, $width = 150, $height = 150, $attributes = array()) {
        if (self::ishttps()):
            $protocol = 'https://';
        else:
            $protocol = 'http://';
        endif;
        $attr = "";
        if (isset($attributes) and is_array($attributes) and !empty($attributes)):
            foreach ($attributes as $attributeName => $attributeValue):
                $attr.= $attributeName . '="' . $attributeValue . '" ';
            endforeach;
        endif;
        $apiUrl = $protocol . "chart.apis.google.com/chart?chs=" . $width . "x" . $height . "&cht=qr&chl=" . urlencode($string);
        return '<img src="' . $apiUrl . '" ' . trim($attr) . ' />';
    }

    /**
     * Search wikipedia
     * @param  string $keyword Keywords to search in wikipedia
     * @return mixed Array or false
     */
    public function wikiSearch($keyword) {
        $apiurl = "http://wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($keyword) . "&format=xml&limit=1";
        $data = self::curl($apiurl);
        $xml = simplexml_load_string($data);
        if ((string)$xml->Section->Item->Description):
            $array['title'] = (string)$xml->Section->Item->Text;
            $array['description'] = (string)$xml->Section->Item->Description;
            $punctuationArray = array(":");
            $lastChar = mb_substr(trim($array['description']), -1, 1, "UTF-8");

            if (!in_array($lastChar, $punctuationArray)):

                $array['url'] = (string)$xml->Section->Item->Url;
                if (isset($xml->Section->Item->Image)):
                    $img = (string)$xml->Section->Item->Image->attributes()->source;
                    $array['image'] = str_replace("/50px-", "/200px-", $img);
                endif;

                return $array;
            endif;
        endif;
        return false;
    }

    /**
     * Read RSS feed as array
     * requires
     * @see http://php.net/manual/en/simplexml.installation.php
     * @param  string $url RSS feed URL
     * @return array Representation of XML feed
     */
    public function rssReader($url) {
        if (strpos($url, "http") === false):
            $url = 'http://' . $url;
        endif;
        $feed = self::curl($url);
        $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);
        return self::objectToArray($xml);
    }

}