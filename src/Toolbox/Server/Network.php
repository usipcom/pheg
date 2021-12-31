<?php

namespace Simtabi\Pheg\Toolbox\Server;

final class Network
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function isConnected($host = 'www.google.com')
    {
        return (bool) @fsockopen($host, 80, $iErrno, $sErrStr, 5);
    }

    /**
     * Check to see if the current page is being server over SSL or not
     * @return boolean
     */
    public function isHttps() {
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'):
            return true;
        endif;
        return false;
    }

    /**
     * Determine if current page request type is ajax
     * @return boolean
     */
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
            return true;
        endif;
        return false;
    }

}