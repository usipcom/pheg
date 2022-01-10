<?php declare(strict_types=1);

namespace Simtabi\Pheg\Core\Support;

use Adbar\Dot;
use Simtabi\Pheg\Core\Loader;
use Simtabi\Pheg\Core\Support\Traits\DataHelpersTrait;
use Simtabi\Pheg\Core\Support\Traits\FormHelpersTrait;
use Simtabi\Pheg\Core\Support\Traits\SupportHelpersTrait;
use Simtabi\Pheg\Pheg;

class Supports
{
    use DataHelpersTrait;
    use FormHelpersTrait;
    use SupportHelpersTrait;

    private Loader $loader;
    private Dot    $data;
    private Dot    $colors;
    private string $key;
    private        $default = null;
    private Pheg   $pheg;
    private bool   $asArray = true;
    private string $fileName;

    /**
     * Create class instance
     *
     * @version      1.0
     * @since        1.0
     */
    private static $instance;

    public static function getInstance(Pheg $pheg) {
        if (isset(self::$instance) && !is_null(self::$instance)) {
            return self::$instance;
        } else {

            $static         = new static();
            $static->loader = new Loader();
            $static->pheg   = $pheg;

            $data           = $static->registerSupportFiles(['supports'], 'config');
            $static->data   = new Dot($data->loader->init());

            return self::$instance = $static;
        }
    }

    private function __construct() {}
    private function __clone() {}

    private function registerSupportFiles(array $files, string $folder): self
    {
        foreach ($files as $file)
        {
            $this->loader->setFolderName($folder)->setFileNames($file);
        }

        return $this;
    }

    /**
     * @param mixed $key
     * @return self
     */
    public function setKey($key): self
    {
        $this->key = trim($key);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function setDefault(mixed $value): self
    {
        $this->default = $this->pheg->filter()->trimIfString($value);
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    public function isAsArray(bool $status = true): self
    {
        $this->asArray = $status;

        return $this;
    }

    /**
     * @param mixed $fileName
     * @return self
     */
    public function setFileName($fileName): self
    {
        $this->fileName = trim($fileName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    public function getData()
    {

        $data = [];

        if ($this->data->has($this->fileName)) {
            $data = $this->data->get($this->fileName);
        }

        $data = $data[$this->key] ?? [];

        if (!empty($this->default) && (is_array($data) && count($data) >= 1)) {
            $data = $this->pheg->arr()->fetch($this->default, $data);
        }
        
        return $this->asArray ? $data : $this->pheg->transfigure()->toObject($data);
    }

}
