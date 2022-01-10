<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

class SocialMedia
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function getTwitterShareCount($url)
    {
        //@todo
    }

    public function getFacebookShareCount($url)
    {
        //@todo
    }

    public function getFacebookLikesCount($page)
    {
        //@todo
    }

    public function getGooglePlusShareCount($url)
    {
        //@todo
    }

    public function getPinterestShareCount($url)
    {
        //@todo
    }

    public function getLinkedInShareCount($url)
    {
        //@todo
    }

    public function getLinkedinCounter($url)
    {
        //@todo
    }

    public function getPinterestCounter($url)
    {
        //@todo
    }

}