<?php

namespace Simtabi\Pheg\Toolbox;

use Simtabi\Pheg\Toolbox\Traits\CountryData\WithContinentsTrait;
use Simtabi\Pheg\Toolbox\Traits\CountryData\WithCountriesTrait;
use Simtabi\Pheg\Toolbox\Traits\CountryData\WithCurrenciesTrait;
use Simtabi\Pheg\Toolbox\Traits\CountryData\WithISOCodesTrait;
use Simtabi\Pheg\Toolbox\Traits\CountryData\WithLanguagesTrait;
use Adbar\Dot;
use DirectoryIterator;
use Simtabi\JsonDB\Services\File\Json2File;
use Simtabi\Pheg\Core\CoreTools;
use Simtabi\Pheg\Toolbox\Traits\CountryData\WithValidatorsTrait;
use stdClass;

final class Countries
{

    use WithContinentsTrait;
    use WithCountriesTrait;
    use WithCurrenciesTrait;
    use WithISOCodesTrait;
    use WithLanguagesTrait;
    use WithValidatorsTrait;

    public const TIMEZONE_AFRICA     = 'africa';
    public const TIMEZONE_AMERICA    = 'america';
    public const TIMEZONE_ANTARCTICA = 'antarctica';
    public const TIMEZONE_ASIA       = 'asia';
    public const TIMEZONE_ATLANTIC   = 'atlantic';
    public const TIMEZONE_AUSTRALIA  = 'australia';
    public const TIMEZONE_EUROPE     = 'europe';
    public const TIMEZONE_INDIAN     = 'indian';
    public const TIMEZONE_PACIFIC    = 'pacific';
    public const TIMEZONE_UTC        = 'utc';

    private string $basePath  = '';
    private bool   $asObject  = false;
    private array  $keys      = [];
    private Dot    $data;
    private array  $raw       = [];
    private array  $loaded    = [];
    private array  $loadFrom  = [];

    public function __construct(string $basePath = '') {
        if (!empty($basePath) && is_string($basePath)) {
            $this->basePath = $basePath;
        }else{
            $this->basePath = CoreTools::PHEG_DIR_PATH .'../../';
        }

        $this->setLoadFrom([
            'countries' => $this->basePath.'annexare/countries-list/data',
            'currency'  => [
                CoreTools::PHEG_DIR_PATH.'/data/currency'
            ],
        ])->initialize();
    }

    public static function invoke(string $basePath = ''): self
    {
        return new self($basePath);
    }

    private function initialize(){

        $autoloadJSONFiles = function (string $directory, $id) {

            $data = [];
            $name = function ($filename){
                return str_replace('.json', '', $filename);
            };

            // loop to get all files
            foreach ((new DirectoryIterator($directory)) as $cacheKey => $fileInfo) {
                if (!$fileInfo->isDot()) {
                    $filename               = $fileInfo->getFilename();
                    $_name                  = $name($filename);
                    $this->raw[$id][$_name] = [
                        'filename' => $filename,
                        'pathname' => $fileInfo->getPathname(),
                    ];
                    $this->setKeys($_name);
                    $this->setLoaded($id);
                    $data[$_name] = Json2File::fileToArray($fileInfo->getPathname());
                }
            }

            return $data;
        };



        $array = [];
        foreach ($this->loadFrom as $key => $item){
            if (is_array($item)) {
                foreach ($item as $value){
                    if (!is_array($value)) {
                        $array[$key] = $autoloadJSONFiles($value, $key);
                    }
                }
            }else{
                $array[$key] = $autoloadJSONFiles($item, $key);
            }
        }
        $this->setData(new Dot($array));
        return $this;
    }


    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    /**
     * @param array $raw
     * @return self
     */
    public function setRaw(array $raw): self
    {
        $this->raw = $raw;
        return $this;
    }



    /**
     * @return array
     */
    public function getLoaded(): array
    {
        return $this->loaded;
    }

    /**
     * @param string $loaded
     * @return self
     */
    public function setLoaded(string $loaded): self
    {
        $this->loaded[] = $loaded;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoadFrom(): array
    {
        return $this->loadFrom;
    }

    /**
     * @param array $loadFrom
     * @return self
     */
    public function setLoadFrom(array $loadFrom): self
    {
        $this->loadFrom = $loadFrom;
        return $this;
    }



    /**
     * @return bool
     */
    public function isAsObject(): bool
    {
        return $this->asObject;
    }

    /**
     * @param bool $asObject
     * @return self
     */
    public function setAsObject(bool $asObject): self
    {
        $this->asObject = $asObject;
        return $this;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param string $keys
     * @return self
     */
    private function setKeys(string $keys): self
    {
        $this->keys[] = $keys;
        return $this;
    }

    /**
     * @param null $request
     * @return object|stdClass
     */
    public function getData($request)
    {
        $request = trim($request);
        $data    = [];

        if ($this->data->has($request)) {
            $data = $this->data->get($request);
        }
        return $this->isAsObject() ? TypeConverter::fromAnyToObject($data) : $data;
    }

    /**
     * @param Dot $data
     * @return self
     */
    private function setData(Dot $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getAll(){
        return $this->isAsObject() ? TypeConverter::fromAnyToObject($this->data->all()) : $this->data->all();
    }

}
