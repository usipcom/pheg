<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

final class Avatar
{

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Get Gravatar image by email.
     *
     * @param string $email
     * @param int $size
     * @param string $rating [g|pg|r|x]
     * @param string $default
     * @return string
     */

    public function getGravatar($email, $size = 200, $is_https = false, $rating = 'g', $default = 'monsterid' ): ?string
    {
        if ( $is_https ) {
            $url = 'https://secure.gravatar.com/';
        } else {
            $url = 'http://www.gravatar.com/';
        }

        return $url . 'avatar/' . md5(strtolower(trim($email))) . '/?d=' . $default . '&s=' . (int) abs( $size ) . '&r=' . $rating;
    }

}