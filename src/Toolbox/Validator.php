<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Simtabi\Pheg\Toolbox\Traits\Validators\AgeValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\ArrayValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\ColorValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\CountriesValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\DataTypeValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\TimeValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\FileSystemValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\EmailValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\HtmlValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\IpAddressValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\JsonValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\NumberValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\PasswordValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\PhoneNumberValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\PostalCodeValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\StringValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\UrlValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\UsernameValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\VersionNumberValidator;
use Simtabi\Pheg\Toolbox\Traits\Validators\WithRespectValidatorsTrait;
use Simtabi\Pheg\Toolbox\Traits\Validators\WithGeneralValidatorsTrait;

final class Validator
{

    use WithGeneralValidatorsTrait;
    use WithRespectValidatorsTrait;

    public function __construct()
    {

    }

    public static function invoke(): self
    {
        return new self();
    }

    public function age(): AgeValidator
    {
        return AgeValidator::invoke();
    }

    public function array(): ArrayValidator
    {
        return ArrayValidator::invoke();
    }

    public function atlas(): CountriesValidator
    {
        return CountriesValidator::invoke();
    }

    public function color(): ColorValidator
    {
        return ColorValidator::invoke();
    }

    public function dataType(): DataTypeValidator
    {
        return DataTypeValidator::invoke();
    }

    public function time(): TimeValidator
    {
        return TimeValidator::invoke();
    }

    public function email(): EmailValidator
    {
        return EmailValidator::invoke();
    }

    public function fileSystem(): FileSystemValidator
    {
        return FileSystemValidator::invoke();
    }

    public function html(): HtmlValidator
    {
        return HtmlValidator::invoke();
    }

    public function ipAddress(): IpAddressValidator
    {
        return IpAddressValidator::invoke();
    }

    public function json(): JsonValidator
    {
        return JsonValidator::invoke();
    }

    public function number(): NumberValidator
    {
        return NumberValidator::invoke();
    }

    public function password(): PasswordValidator
    {
        return PasswordValidator::invoke();
    }

    public function phoneNumber(): PhoneNumberValidator
    {
        return PhoneNumberValidator::invoke();
    }

    public function postalCode(): PostalCodeValidator
    {
        return PostalCodeValidator::invoke();
    }

    public function string(): StringValidator
    {
        return StringValidator::invoke();
    }

    public function url(): UrlValidator
    {
        return UrlValidator::invoke();
    }

    public function username(): UsernameValidator
    {
        return UsernameValidator::invoke();
    }

    public function versionNumber(): VersionNumberValidator
    {
        return VersionNumberValidator::invoke();
    }

}
