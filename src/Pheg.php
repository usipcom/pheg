<?php

namespace Simtabi\Pheg;

use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Core\Support\Data;
use Simtabi\Pheg\Toolbox\Base64;
use Simtabi\Pheg\Toolbox\Breadcrumbs;
use Simtabi\Pheg\Toolbox\Colors\Colors;
use Simtabi\Pheg\Toolbox\CopyrightText;
use Simtabi\Pheg\Toolbox\Countries;
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

    public function getColor(): Colors
    {
        return Colors::invoke();
    }

    public function getBase64Uid(): Base64
    {
        return Base64::invoke();
    }

    public function getBreadcrumbs(?string $separator = null): Breadcrumbs
    {
        return  Breadcrumbs::invoke($separator);
    }

    public function getCopyrightBuilder(): CopyrightText
    {
        return CopyrightText::invoke();
    }

    public function getAtlas(): Countries
    {
        return Countries::invoke();
    }

    public function getDataHandler(): DataHandler
    {
        return DataHandler::invoke();
    }

    public function getHtml2Text(): Html2Text
    {
        return Html2Text::invoke();
    }

    public function getHtml5Cleaner(): HtmlCleaner
    {
        return HtmlCleaner::invoke();
    }

    public function getIntel(): Intel
    {
        return Intel::invoke();
    }

    public function getUuid(): UuidGenerator
    {
        return UuidGenerator::invoke();
    }

    public function getSanitizer(): Sanitize
    {
        return Sanitize::invoke();
    }

    public function getSimpleTimer(): SimpleTimer
    {
        return SimpleTimer::invoke();
    }

    public function getSslToolkit(): SSLToolkit
    {
        return SSLToolkit::invoke();
    }

    public function getTypeConverter(): TypeConverter
    {
        return TypeConverter::invoke();
    }

    public function getValidator(): Validators
    {
        return Validators::invoke();
    }

}