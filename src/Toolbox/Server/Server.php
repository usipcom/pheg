<?php

namespace Simtabi\Pheg\Toolbox\Server;

use Simtabi\Pheg\Toolbox\Server\Entities\Intel;
use Simtabi\Pheg\Toolbox\Server\Entities\IP;
use Simtabi\Pheg\Toolbox\Server\Entities\Network;
use Simtabi\Pheg\Toolbox\Server\Entities\SSLToolkit;

class Server
{
    public function __construct() {}

    public function intel(): Intel
    {
        return new Intel();
    }

    public function ip(): IP
    {
        return new IP;
    }

    public function network(): Network
    {
        return new Network;
    }

    public function sslToolkit(array $url = [], string $dateFormat = 'U', string $formatString = 'Y-m-d\TH:i:s\Z', ?string $timeZone = null, float $timeOut = 30): SSLToolkit
    {
        return new SSLToolkit($url, $dateFormat, $formatString, $timeZone, $timeOut);
    }

    /**
     * Get current Php version information
     *
     * @return array
     */
    public function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;
        preg_match('#^\\d+(\\.\\d+)*#', $currentVersionFull, $filtered);
        $currentVersion     = $filtered[0];

        return [
            'full'    => $currentVersionFull,
            'version' => $currentVersion,
        ];
    }

    /**
     * Check PHP version requirement.
     *
     * @param string|null $minPhpVersion
     * @return array
     */
    public function checkPhpVersion(?string $minPhpVersion = null): array
    {
        $minVersionPhp     = $minPhpVersion;
        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported         = false;

        if (version_compare($currentPhpVersion['version'], $minVersionPhp, '>=') >= 0) {
            $supported = true;
        }

        return [
            'full'      => $currentPhpVersion['full'],
            'current'   => $currentPhpVersion['version'],
            'minimum'   => $minVersionPhp,
            'supported' => $supported,
        ];
    }

    /**
     * Verifies if the mod_rewrite module is enabled
     *
     * @return boolean True if the module is enabled.
     */
    public function isModRewriteEnabled(): bool
    {
        return (bool) isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On';
    }

    /**
     * Gets all Server Headers
     *
     * @return array
     */
    public function getAllServerHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
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

    /**
     * Get package root path
     *
     * @param string|null $resource
     * @param int $levels
     * @return string
     */
    public function getRootPath(?string $resource = null, int $levels = 2): string
    {
        return dirname(__DIR__, $levels) . "/" . (!empty($resource) ? "{$resource}" : "");
    }

}