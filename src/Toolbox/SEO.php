<?php

namespace Simtabi\Pheg\Toolbox;

class SEO
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Get keyword suggestion from Google
     * @param  string $keyword keyword to get suggestions for
     * @return mixed array of keywords or false
     */
    public function getKeywordSuggestionsFromGoogle($keyword) {
        $keywords = array();
        $data = self::curl('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q=' . urlencode($keyword));
        if (($data = json_decode($data, true)) !== null):
            if (!empty($data[1])):
                return $data[1];
            endif;
        endif;
        return false;
    }

    /**
     * Get Alexa ranking for domain name
     * @param  string $domain [description]
     * @return mixed false if ranking is found, otherwise integer
     */
    public function getAlexaRank($domain) {
        $domain = preg_replace('~^https?://~', '', $domain);
        $alexa = "http://data.alexa.com/data?cli=10&dat=s&url=%s";
        $request_url = sprintf($alexa, urlencode($domain));
        $xml = simplexml_load_file($request_url);
        if (!isset($xml->SD[1])):
            return false;
        endif;
        $nodeAttributes = $xml->SD[1]->POPULARITY->attributes();
        $text = (int)$nodeAttributes['TEXT'];
        return $text;
    }

    /**
     * Get Google page rank for url
     * @param  string $url URL to get Google Page rank for
     * @return mixed integer or false
     */
    public function getGooglePageRank($url) {

        // based on code by Mohammed Hijazi
        function StrToNum($Str, $Check, $Magic) {
            $Int32Unit = 4294967296;

            // 2^32
            $length = strlen($Str);
            for ($i = 0; $i < $length; $i++):
                $Check*= $Magic;
                if ($Check >= $Int32Unit):
                    $Check = ($Check - $Int32Unit * (int)($Check / $Int32Unit));
                    $Check = ($Check < - 2147483648) ? ($Check + $Int32Unit) : $Check;
                endif;
                $Check+= ord($Str{$i});
            endfor;
            return $Check;
        }
        function HashURL($String) {
            $Check1 = StrToNum($String, 0x1505, 0x21);
            $Check2 = StrToNum($String, 0, 0x1003F);
            $Check1 >>= 2;
            $Check1 = (($Check1 >> 4) & 0x3FFFFC0) | ($Check1 & 0x3F);
            $Check1 = (($Check1 >> 4) & 0x3FFC00) | ($Check1 & 0x3FF);
            $Check1 = (($Check1 >> 4) & 0x3C000) | ($Check1 & 0x3FFF);
            $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) << 2) | ($Check2 & 0xF0F);
            $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000);
            return ($T1 | $T2);
        }
        function CheckHash($Hashnum) {
            $CheckByte = 0;
            $Flag = 0;
            $HashStr = sprintf('%u', $Hashnum);
            $length = strlen($HashStr);
            for ($i = $length - 1; $i >= 0; $i--):
                $Re = $HashStr{$i};
                if (1 === ($Flag % 2)):
                    $Re+= $Re;
                    $Re = (int)($Re / 10) + ($Re % 10);
                endif;
                $CheckByte+= $Re;
                $Flag++;
            endfor;
            $CheckByte%= 10;
            if (0 !== $CheckByte):
                $CheckByte = 10 - $CheckByte;
                if (1 === ($Flag % 2)):
                    if (1 === ($CheckByte % 2)):
                        $CheckByte+= 9;
                    endif;
                    $CheckByte >>= 1;
                endif;
            endif;
            return '7' . $CheckByte . $HashStr;
        }
        $query = "http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=" . CheckHash(HashURL($url)) . "&features=Rank&q=info:" . $url . "&num=100&filter=0";

        $data = file_get_contents($query);
        $pos = strpos($data, "Rank_");

        if ($pos === false):
            return false;
        else:
            $pagerank = substr($data, $pos + 9);
            return (int)$pagerank;
        endif;
    }

    /**
     * Shorten URL via tinyurl.com service
     * @param  string $url URL to shorten
     * @return mixed shortend url or false
     */
    public function getTinyUrl($url) {
        if (strpos($url, "http") === false):
            $url = 'http://' . $url;
        endif;
        $gettiny = self::curl("http://tinyurl.com/api-create.php?url=" . $url);
        if (isset($gettiny) and !empty($gettiny)):
            return $gettiny;
        endif;
        return false;
    }

    /**
     * Get information on a short URL. Find out where it goes
     * @param  string $shortURL shortened URL
     * @return mixed full url or false
     */
    public function expandShortUrl($shortURL) {
        if (!empty($shortURL)):
            $headers = get_headers($shortURL, 1);
            if (isset($headers["Location"])):
                return $headers["Location"];
            else:
                $data = self::curl($shortURL);
                preg_match_all('/<[\s]*meta[\s]*http-equiv="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $data, $match);
                if (isset($match) && is_array($match) && count($match) == 3):
                    $originals = $match[0];
                    $names = $match[1];
                    $values = $match[2];
                    if ((isset($originals) and isset($names) and isset($values)) and count($originals) == count($names) && count($names) == count($values)):
                        $metaTags = array();
                        for ($i = 0, $limit = count($names); $i < $limit; $i++):
                            $metaTags[$names[$i]] = array('html' => htmlentities($originals[$i]), 'value' => $values[$i]);
                        endfor;
                    endif;
                endif;
                if (isset($metaTags['refresh']['value']) and !empty($metaTags['refresh']['value'])):
                    $returnData = explode("=", $metaTags['refresh']['value']);
                    if (isset($returnData[1]) and !empty($returnData[1])):
                        return $returnData[1];
                    endif;
                endif;
            endif;
        endif;
        return false;
    }

}