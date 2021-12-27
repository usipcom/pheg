<?php

namespace Simtabi\Pheg;

use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Core\Support\Data;
use Simtabi\Pheg\Toolbox\Base64;
use Simtabi\Pheg\Toolbox\Breadcrumbs;
use Simtabi\Pheg\Toolbox\Colors\Colors;
use Simtabi\Pheg\Toolbox\CopyrightText;
use Simtabi\Pheg\Toolbox\Countries\Countries;
use Simtabi\Pheg\Toolbox\DataHandler;
use Simtabi\Pheg\Toolbox\Html2Text;
use Simtabi\Pheg\Toolbox\HtmlCleaner;
use Simtabi\Pheg\Toolbox\Intel;
use Simtabi\Pheg\Toolbox\Sanitize;
use Simtabi\Pheg\Toolbox\SimpleTimer;
use Simtabi\Pheg\Toolbox\Transfigures\TypeConverter;
use Simtabi\Pheg\Toolbox\UuidGenerator;
use Simtabi\Pheg\Toolbox\SSLToolkit;
use Respect\Validation\Validator as Respect;

class Pheg
{

    public static Respect $respectValidation;

    /**
     * Create class instance
     *
     * @version      1.0
     * @since        1.0
     */
    private static $instance;

    public static function getInstance() {
        if (isset(self::$instance) && !is_null(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = new static();
            self::$respectValidation = new Respect();
            return self::$instance;
        }
    }

    private function __construct(){}
    private function __clone() {}

    public function data(): Data
    {
        return Data::getInstance(self::$instance);
    }

    public function color(): Colors
    {
        return Colors::invoke();
    }

    public function base64Uid(): Base64
    {
        return Base64::invoke();
    }

    public function breadcrumbs(?string $separator = null): Breadcrumbs
    {
        return  Breadcrumbs::invoke($separator);
    }

    public function copyrightBuilder(): CopyrightText
    {
        return CopyrightText::invoke();
    }

    public function atlas(): Countries
    {
        return Countries::invoke();
    }

    public function dataHandler(): DataHandler
    {
        return DataHandler::invoke();
    }

    public function html2Text(): Html2Text
    {
        return Html2Text::invoke();
    }

    public function html5Cleaner(): HtmlCleaner
    {
        return HtmlCleaner::invoke();
    }

    public function intel(): Intel
    {
        return Intel::invoke();
    }

    public function uuid(): UuidGenerator
    {
        return UuidGenerator::invoke();
    }

    public function sanitizer(): Sanitize
    {
        return Sanitize::invoke();
    }

    public function simpleTimer(): SimpleTimer
    {
        return SimpleTimer::invoke();
    }

    public function sslToolkit(array $url = [], string $dateFormat = 'U', string $formatString = 'Y-m-d\TH:i:s\Z', ?string $timeZone = null, float $timeOut = 30): SSLToolkit
    {
        return SSLToolkit::invoke($url, $dateFormat, $formatString, $timeZone, $timeOut);
    }

    public function typeConverter(): TypeConverter
    {
        return TypeConverter::invoke();
    }

    public function validator(): Validators
    {
        return Validators::invoke();
    }

}