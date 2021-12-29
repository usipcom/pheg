<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Exception;
use Simtabi\Enekia\Validators;

final class Email
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Create random email
     *
     * @param int $userNameLength
     * @return string
     * @throws Exception
     */
    public function random(int $userNameLength = 10): string
    {
        return Str::invoke()->random($userNameLength) . '@' . Str::invoke()->random(5) . '.com';
    }

    /**
     * Check if email(s) is(are) valid. You can send one or an array of emails.
     *
     * @param string|array $emails
     * @return array
     */
    public function check($emails): array
    {
        $result = [];

        if (empty($emails)) {
            return $result;
        }

        $emails = $this->handleEmailsInput($emails);

        foreach ($emails as $email) {
            if (!$this->isValid($email)) {
                continue;
            }
            if (!in_array($email, $result, true)) {
                $result[] = $email;
            }
        }

        return $result;
    }

    /**
     * Check for DNS MX records of the email domain. Notice that a
     * (temporary) DNS error will have the same result as no records
     * were found. Code coverage ignored because this method requires
     * DNS requests that could not be reliable.
     *
     * @param string $email
     * @return bool
     */
    public function checkDns(string $email): bool
    {
        if (!$this->isValid($email)) {
            return false;
        }

        $domain = $this->extractDomain($email);

        return !(checkdnsrr($domain, 'MX') === false);
    }

    /**
     * Get domains from email addresses. The not valid email addresses
     * will be skipped.
     *
     * @param string|array $emails
     * @return array
     */
    public function getDomain($emails): array
    {
        $result = [];

        if (empty($emails)) {
            return $result;
        }

        $emails = $this->handleEmailsInput($emails);

        foreach ($emails as $email) {
            if (!$this->isValid($email)) {
                continue;
            }

            $domain = $this->extractDomain($email);
            if (!empty($domain) && !in_array($domain, $result, true)) {
                $result[] = $domain;
            }
        }

        return $result;
    }

    /**
     * Get domains from email addresses in alphabetical order.
     *
     * @param array $emails
     * @return array
     */
    public function getDomainSorted(array $emails): array
    {
        $domains = $this->getDomain($emails);
        sort($domains, SORT_STRING);

        return $domains;
    }

    /**
     * Generates an Gravatar URL.
     *
     * Size of the image:
     * * The default size is 32px, and it can be anywhere between 1px up to 2048px.
     * * If requested any value above the allowed range, then the maximum is applied.
     * * If requested any value bellow the minimum, then the default is applied.
     *
     * Default image:
     * * It can be an URL to an image.
     * * Or one of built in options that Gravatar has. See Email::getGravatarBuiltInImages().
     * * If none is defined then a built in default is used. See Email::getGravatarBuiltInDefaultImage().
     *
     * @param string $email
     * @param int    $size
     * @param string $defaultImage
     * @return null|string
     * @link http://en.gravatar.com/site/implement/images/
     */
    public function getGravatarUrl(string $email, int $size = 32, string $defaultImage = 'identicon'): ?string
    {
        if (empty($email) || !$this->isValid($email)) {
            return null;
        }

        $hash  = md5(strtolower(trim($email)));
        $parts = ['scheme' => 'http', 'host' => 'www.gravatar.com'];

        if (Url::invoke()->isHttps()) {
            $parts = ['scheme' => 'https', 'host' => 'secure.gravatar.com'];
        }

        // Get size
        $size = Numbers::invoke()->limit(Filter::invoke()->int($size), 32, 2048);

        // Prepare default images
        $defaultImage = trim($defaultImage);
        if (preg_match('/^(http|https)./', $defaultImage)) {
            $defaultImage = urldecode($defaultImage);
        } else {
            $defaultImage = strtolower($defaultImage);
            if (!Validators::invoke()->array()->isInArray($defaultImage, $this->getGravatarBuiltInImages())) {
                $defaultImage = $this->getGravatarBuiltInImages()[2];
            }
        }

        // Build full url
        $parts['path'] = '/avatar/' . $hash . '/';
        $parts['query'] = [
            's' => $size,
            'd' => $defaultImage,
        ];

        return Url::invoke()->create($parts);
    }

    /**
     * Returns gravatar supported placeholders
     *
     * @return array
     */
    public function getGravatarBuiltInImages(): array
    {
        return [
            '404',
            'mm',
            'identicon',
            'monsterid',
            'wavatar',
            'retro',
            'blank',
        ];
    }

    /**
     * Returns true if string has valid email format
     *
     * @param string|null $email
     * @return bool
     */
    public function isValid(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        $email = filter_var($email, FILTER_SANITIZE_STRING);

        return !(filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }

    /**
     * @param string $email
     * @return string
     */
    private function extractDomain(string $email): string
    {
        $parts = explode('@', $email);
        $domain = array_pop($parts);

        if (System::invoke()->isFunc('idn_to_utf8')) {
            return (string)idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);
        }

        return $domain;
    }

    /**
     * Transforms strings in array, and remove duplicates.
     * Using array_keys array_flip because is faster than array_unique:
     * array_unique O(n log n)
     * array_flip O(n)
     *
     * @link http://stackoverflow.com/questions/8321620/array-unique-vs-array-flip
     * @param string|array $emails
     * @return array
     */
    private function handleEmailsInput($emails): array
    {
        return is_array($emails) ? array_keys(array_flip($emails)) : [$emails];
    }



    /**
     * method masks the username of an email address
     *
     * @param string $email the email address to mask
     * @param int $level the percent of the username to mask
     * @param string $char the character to use to mask with
     * @return false|string $result
     */
    public function mask($email, $level = 50, $char = '*' ){

        if(!empty($email)){
            list( $user, $domain ) = preg_split("/@/", $email );

            //username parts mask
            $len_user            = strlen( $user );
            $username_mask_count = floor( $len_user * $level /100 );
            $username_offset     = floor( ( $len_user - $username_mask_count ) / 2 );
            $masked_username     = substr( $user, 0, (int)$username_offset )
                .str_repeat( $char, (int)$username_mask_count)
                .substr( $user, (int)$username_mask_count);

            //domain part mask
            $len_domain          = strlen( $user );
            $random              = rand(60,90);
            $domain_mask_count   = floor( $len_domain * $random /100 );
            $domain_offset       = floor( ( $len_domain - $domain_mask_count ) / 2 );
            $masked_domain       = substr( $domain, 0, (int)$domain_offset )
                .str_repeat( $char, (int)$domain_mask_count)
                .substr( $domain, (int)$domain_mask_count);

            //return results
            return( $masked_username.'@'.$masked_domain );

        }

        return false;
    }

    /**
     * Obfuscates email addresses
     * ryan@yellowpencil.com == ry**@ye**********.com
     *
     * @param string $email
     *
     * @return string
     */
    function obfuscate(string $email)
    {
        $out = [];
        foreach (explode("@", $email) as $str) {
            array_push(
                $out,
                preg_replace_callback(
                    '/(?<=^.{2})[^.]*/',
                    function ($m) {
                        return str_repeat('*', strlen($m[0]));
                    },
                    $str
                )
            );
        }

        return implode('@', $out);
    }

    public function encode($email='info@domain.com', $linkText='Contact Us', $attrs ='class="emailencoder"' )
    {
        // remplazar aroba y puntos
        $email = str_replace('@', '&#64;', $email);
        $email = str_replace('.', '&#46;', $email);
        $email = str_split($email, 5);

        $linkText = str_replace('@', '&#64;', $linkText);
        $linkText = str_replace('.', '&#46;', $linkText);
        $linkText = str_split($linkText, 5);

        $part1 = '<a href="ma';
        $part2 = 'ilto&#58;';
        $part3 = '" '. $attrs .' >';
        $part4 = '</a>';

        // generamos el Javascript
        $encoded = '<script type="text/javascript">';
        $encoded .= "document.write('$part1');";
        $encoded .= "document.write('$part2');";
        foreach($email as $e)
        {
            $encoded .= "document.write('$e');";
        }
        $encoded .= "document.write('$part3');";
        foreach($linkText as $l)
        {
            $encoded .= "document.write('$l');";
        }
        $encoded .= "document.write('$part4');";
        $encoded .= '</script>';

        return $encoded;
    }

}
