<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

final class Intel
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }


    public function isBot()
    {

    }

    public function getDeviceInfo()
    {

    }

    public function getUserAgent()
    {

    }

    public function getLocation()
    {

    }

    public function isHandHeldDevice()
    {

    }

    public function getDeviceType()
    {

    }


    public function getIPInfo() {

    }

    public function getIP($getHostName = false) {

        // http://chriswiegman.com/2014/05/getting-correct-ip-address-php/
        //Just get the headers if we can or else use the SERVER global
        $hostname = null;
        if ( function_exists( 'apache_request_headers' ) ) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $realIp = $headers['X-Forwarded-For'];
        }
        elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
            $realIp = $headers['HTTP_X_FORWARDED_FOR'];
        }
        else {
            if( (($_SERVER['HTTP_HOST']) === 'localhost') || (($_SERVER['SERVER_NAME']) === 'localhost') ){
                $hostname = 'localhost';
                $realIp   = '127.0.0.1';
            }
            elseif( (($_SERVER['HTTP_HOST']) === '127.0.0.1') || (($_SERVER['SERVER_NAME']) === '127.0.0.1') ){
                $hostname = 'localhost';
                $realIp   = '127.0.0.1';
            }
            else{
                $hostname = $_SERVER['SERVER_NAME'];
                $realIp   = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
            }
        }

        return $getHostName ? $hostname : $realIp;
    }

    public function getIpCountry($ip = null, $isoCode = true){

    }

}