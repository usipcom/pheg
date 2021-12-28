<?php

namespace Simtabi\Pheg;

use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Core\Support\PhegData;
use Respect\Validation\Validator as Respect;
use Simtabi\Pheg\Toolbox\Arr;
use Simtabi\Pheg\Toolbox\Avatar;
use Simtabi\Pheg\Toolbox\Base64;
use Simtabi\Pheg\Toolbox\Base64Uid;
use Simtabi\Pheg\Toolbox\Breadcrumbs;
use Simtabi\Pheg\Toolbox\Cli;
use Simtabi\Pheg\Toolbox\Colors\Colors;
use Simtabi\Pheg\Toolbox\CopyrightText;
use Simtabi\Pheg\Toolbox\Countries\Countries;
use Simtabi\Pheg\Toolbox\CsvParser;
use Simtabi\Pheg\Toolbox\Data\DataFactory;
use Simtabi\Pheg\Toolbox\Dates;
use Simtabi\Pheg\Toolbox\Email;
use Simtabi\Pheg\Toolbox\Env;
use Simtabi\Pheg\Toolbox\File\File;
use Simtabi\Pheg\Toolbox\FileSystem;
use Simtabi\Pheg\Toolbox\Filter;
use Simtabi\Pheg\Toolbox\Html;
use Simtabi\Pheg\Toolbox\Html2Text;
use Simtabi\Pheg\Toolbox\HtmlCleaner;
use Simtabi\Pheg\Toolbox\Http;
use Simtabi\Pheg\Toolbox\Humanize;
use Simtabi\Pheg\Toolbox\Image;
use Simtabi\Pheg\Toolbox\Input;
use Simtabi\Pheg\Toolbox\Intel;
use Simtabi\Pheg\Toolbox\IP;
use Simtabi\Pheg\Toolbox\JSON\JSON;
use Simtabi\Pheg\Toolbox\Name;
use Simtabi\Pheg\Toolbox\Number;
use Simtabi\Pheg\Toolbox\Password;
use Simtabi\Pheg\Toolbox\PhoneNumber;
use Simtabi\Pheg\Toolbox\PhpDocs;
use Simtabi\Pheg\Toolbox\Request;
use Simtabi\Pheg\Toolbox\Sanitize;
use Simtabi\Pheg\Toolbox\Serialize;
use Simtabi\Pheg\Toolbox\SimpleTimer;
use Simtabi\Pheg\Toolbox\Slug;
use Simtabi\Pheg\Toolbox\SqlHandler;
use Simtabi\Pheg\Toolbox\SSLToolkit;
use Simtabi\Pheg\Toolbox\Stats;
use Simtabi\Pheg\Toolbox\Str;
use Simtabi\Pheg\Toolbox\System;
use Simtabi\Pheg\Toolbox\Timer;
use Simtabi\Pheg\Toolbox\Transfigures\ArrayToXml;
use Simtabi\Pheg\Toolbox\Transfigures\ArrayToXmlConfig;
use Simtabi\Pheg\Toolbox\Transfigures\TypeConverter;
use Simtabi\Pheg\Toolbox\Transfigures\Xml2Array;
use Simtabi\Pheg\Toolbox\Transfigures\XmlResponse;
use Simtabi\Pheg\Toolbox\Transfigures\XmlToArrayConfig;
use Simtabi\Pheg\Toolbox\Url;
use Simtabi\Pheg\Toolbox\UuidGenerator;
use Simtabi\Pheg\Toolbox\Xml;

class Pheg
{

    /**
     * Create class instance
     *
     * @version      1.0
     * @since        1.0
     */
    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance) || is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct(){}
    private function __clone() {}

    public function data(): PhegData
    {
        return PhegData::getInstance(self::$instance);
    }

    public function validator(): Validators
    {
        return Validators::invoke();
    }

    ///
    ///
    ///

    public function colors(): Colors
    {
        return Colors::invoke();
    }

    public function countries(): Countries
    {
        return Countries::invoke();
    }

    public function dataFactory(): DataFactory
    {
        return DataFactory::invoke();
    }

    public function file($path, $mode): File
    {
        return File::invoke($path, $mode);
    }

    public function json(): JSON
    {
        return JSON::invoke();
    }

    public function arrayToXml(): ArrayToXml
    {
        return ArrayToXml::invoke();
    }

    public function typeConverter(): TypeConverter
    {
        return TypeConverter::invoke();
    }

    public function xml2Array(): Xml2Array
    {
        return Xml2Array::invoke();
    }

    public function xmlResponse(array $array): XmlResponse
    {
        return XmlResponse::invoke($array);
    }












    public function fileSystem(): FileSystem
    {
        return FileSystem::invoke();
    }

    public function arr(): Arr
    {
        return Arr::invoke();
    }

    public function avatar(): Avatar
    {
        return Avatar::invoke();
    }

    public function base64(): Base64
    {
        return Base64::invoke();
    }

    public function base64Uid(): Base64Uid
    {
        return Base64Uid::invoke();
    }

    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::invoke();
    }

    public function cli(): Cli
    {
        return Cli::invoke();
    }

    public function copyrightText(): CopyrightText
    {
        return CopyrightText::invoke();
    }

    public function csvParser(): CsvParser
    {
        return CsvParser::invoke();
    }

    public function dates(): Dates
    {
        return Dates::invoke();
    }

    public function email(): Email
    {
        return Email::invoke();
    }

    public function env(): Env
    {
        return Env::invoke();
    }

    public function filter(): Filter
    {
        return Filter::invoke();
    }

    public function html(): Html
    {
        return Html::invoke();
    }

    public function html2Text(): Html2Text
    {
        return Html2Text::invoke();
    }

    public function htmlCleaner(): HtmlCleaner
    {
        return HtmlCleaner::invoke();
    }

    public function http(): Http
    {
        return Http::invoke();
    }

    public function humanize(): Humanize
    {
        return Humanize::invoke();
    }

    public function image(): Image
    {
        return Image::invoke();
    }

    public function input(): Input
    {
        return Input::invoke();
    }

    public function intel(): Intel
    {
        return Intel::invoke();
    }

    public function ip(): IP
    {
        return IP::invoke();
    }

    public function name(): Name
    {
        return Name::invoke();
    }

    public function number(): Number
    {
        return Number::invoke();
    }

    public function password(): Password
    {
        return Password::invoke();
    }

    public function phoneNumber(): PhoneNumber
    {
        return PhoneNumber::invoke();
    }

    public function phpDocs(): PhpDocs
    {
        return PhpDocs::invoke();
    }

    public function request(): Request
    {
        return Request::invoke();
    }

    public function sanitize(): Sanitize
    {
        return Sanitize::invoke();
    }

    public function serialize(): Serialize
    {
        return Serialize::invoke();
    }

    public function simpleTimer(): SimpleTimer
    {
        return SimpleTimer::invoke();
    }

    public function slug(string $string, $separator = '_', array $args = []): Slug
    {
        return Slug::invoke($string, $separator, $args);
    }

    public function sqlHandler(): SqlHandler
    {
        return SqlHandler::invoke();
    }

    public function sslToolkit(array $url = [], string $dateFormat = 'U', string $formatString = 'Y-m-d\TH:i:s\Z', ?string $timeZone = null, float $timeOut = 30): SSLToolkit
    {
        return SSLToolkit::invoke($url, $dateFormat, $formatString, $timeZone, $timeOut);
    }

    public function stats(): Stats
    {
        return Stats::invoke();
    }

    public function str(): Str
    {
        return Str::invoke();
    }

    public function system(): System
    {
        return System::invoke();
    }

    public function timer(): Timer
    {
        return Timer::invoke();
    }

    public function url(): Url
    {
        return Url::invoke();
    }

    public function uuidGenerator(): UuidGenerator
    {
        return UuidGenerator::invoke();
    }

    public function xml(): Xml
    {
        return Xml::invoke();
    }

    /*
    function dev()
    {

        $str = "

        ";

        $t = '';
        foreach( explode(PHP_EOL, $str) as $value ){
            $class = trim($value);
            if (!empty($value)) {
                $name = strlen($class) > 3 ? lcfirst($class) : strtolower($class);
                $t   .= "
                public function $name(): $class
                {
                    return $class::invoke();
                }
            "."\n";
            }
        }

        echo $t;
    }
    */

}
