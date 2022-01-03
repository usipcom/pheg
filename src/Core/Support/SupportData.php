<?php declare(strict_types=1);

namespace Simtabi\Pheg\Core\Support;

use Adbar\Dot;
use Simtabi\Pheg\Core\Loader;
use Simtabi\Pheg\Core\Support\Traits\DataHelpersTrait;
use Simtabi\Pheg\Core\Support\Traits\FormHelpersTrait;
use Simtabi\Pheg\Core\Support\Traits\SupportHelpersTrait;
use Simtabi\Pheg\Pheg;
use Simtabi\Pheg\Toolbox\Transfigures\Transfigure;

class SupportData
{
    use DataHelpersTrait;
    use FormHelpersTrait;
    use SupportHelpersTrait;

    private Loader $loader;
    private Dot    $data;
    private Dot    $colors;
    private string $key;
    private        $default    = null;
    private Pheg   $pheg;

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
            self::$instance = new static();

            self::$instance->loader = new Loader();
            self::$instance->pheg   = $pheg;
            self::$instance->data   = new Dot(
                Transfigure::invoke()->toArray(
                    self::$instance->loader
                        ->setFolderName('config')
                        ->setFileNames(['support_data'])
                        ->toObject()->support_data
                )
            );

            return self::$instance;
        }
    }

    private function __construct() {}
    private function __clone() {}

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
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
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    /**
     * @param string|null $default
     * @return self
     */
    public function setDefault(?string $default): self
    {
        $this->default = trim($default);
        return $this;
    }

    public function getData()
    {

        $data = [];
        if ($this->data->has($this->key)) {
            $data = $this->data->get($this->key);
        }

        if (!empty($this->default) && (is_array($data) && count($data) > 0)) {
            $data = $this->pheg->arr()->fetch($this->default, $data);
        }

        return $data;
    }

}