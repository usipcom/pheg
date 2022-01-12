<?php

namespace Simtabi\Pheg;

use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Core\Support\Supports;
use Simtabi\Pheg\Toolbox\Arr\Arr;
use Simtabi\Pheg\Toolbox\Asset;
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
use Simtabi\Pheg\Toolbox\Helpers;
use Simtabi\Pheg\Toolbox\Time;
use Simtabi\Pheg\Toolbox\Email;
use Simtabi\Pheg\Toolbox\Env;
use Simtabi\Pheg\Toolbox\Media\File\FileSystem;
use Simtabi\Pheg\Toolbox\Filter;
use Simtabi\Pheg\Toolbox\HTML\Html;
use Simtabi\Pheg\Toolbox\Http;
use Simtabi\Pheg\Toolbox\Humanize;
use Simtabi\Pheg\Toolbox\Input;
use Simtabi\Pheg\Toolbox\Media\Media;
use Simtabi\Pheg\Toolbox\JSON\JSON;
use Simtabi\Pheg\Toolbox\Name;
use Simtabi\Pheg\Toolbox\Password;
use Simtabi\Pheg\Toolbox\PhoneNumber;
use Simtabi\Pheg\Toolbox\PhpDocs;
use Simtabi\Pheg\Toolbox\Request;
use Simtabi\Pheg\Toolbox\Sanitize;
use Simtabi\Pheg\Toolbox\SEO;
use Simtabi\Pheg\Toolbox\Serialize;
use Simtabi\Pheg\Toolbox\Server\Server;
use Simtabi\Pheg\Toolbox\SimpleTimer;
use Simtabi\Pheg\Toolbox\SocialMedia;
use Simtabi\Pheg\Toolbox\SqlHandler;
use Simtabi\Pheg\Toolbox\Stats;
use Simtabi\Pheg\Toolbox\Str;
use Simtabi\Pheg\Toolbox\System;
use Simtabi\Pheg\Toolbox\Timer;
use Simtabi\Pheg\Toolbox\Transfigures\Transfigure;
use Simtabi\Pheg\Toolbox\Url;
use Simtabi\Pheg\Toolbox\Uuid;
use Simtabi\Pheg\Toolbox\Vars;
use Simtabi\Pheg\Toolbox\WebServices;
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

    public function supports(): Supports
    {
        return Supports::getInstance(self::$instance);
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

    public function json(): JSON
    {
        return JSON::invoke();
    }

    public function media(): Media
    {
        return Media::invoke();
    }

    public function server(): Server
    {
        return Server::invoke();
    }

    public function transfigure(): Transfigure
    {
        return Transfigure::invoke();
    }




    public function fileSystem(): FileSystem
    {
        return FileSystem::invoke();
    }



    public function arr(): Arr
    {
        return Arr::invoke();
    }

    public function asset(): Asset
    {
        return Asset::invoke();
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

    public function helpers(): Helpers
    {
        return Helpers::invoke();
    }

    public function http(): Http
    {
        return Http::invoke();
    }

    public function humanize(): Humanize
    {
        return Humanize::invoke();
    }

    public function input(): Input
    {
        return Input::invoke();
    }


    public function name(): Name
    {
        return Name::invoke();
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

    public function seo(): SEO
    {
        return SEO::invoke();
    }

    public function serialize(): Serialize
    {
        return Serialize::invoke();
    }

    public function simpleTimer(): SimpleTimer
    {
        return SimpleTimer::invoke();
    }

    public function socialMedia(): SocialMedia
    {
        return SocialMedia::invoke();
    }

    public function sqlHandler(): SqlHandler
    {
        return SqlHandler::invoke();
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

    public function time(): Time
    {
        return Time::invoke();
    }

    public function timer(): Timer
    {
        return Timer::invoke();
    }

    public function url(): Url
    {
        return Url::invoke();
    }

    public function uuid(): Uuid
    {
        return Uuid::invoke();
    }

    public function vars(): Vars
    {
        return Vars::invoke();
    }

    public function webServices(): WebServices
    {
        return WebServices::invoke();
    }

    public function xml(): Xml
    {
        return Xml::invoke();
    }

}



