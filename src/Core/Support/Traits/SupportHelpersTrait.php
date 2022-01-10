<?php declare(strict_types=1);

namespace Simtabi\Pheg\Core\Support\Traits;

trait SupportHelpersTrait
{

    public function quickAccess($key, $data)
    {
        if (empty($key)) {
            return null;
        }
        return $this->pheg->fetch($key, $data);
    }

}
