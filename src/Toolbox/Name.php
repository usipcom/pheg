<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Illuminate\Database\Eloquent\Model;
use stdClass;

final class Name
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function make($object, $substitute = false): bool|string
    {

        $validate = pheg()->validator()->transfigure();

        if ($validate->isArray($object))
        {
            $object = pheg()->transfigure()->toObject($object);
        }

        if (!$validate->isObject($object))
        {
            return false;
        }

        $salutation = ucfirst($object->salutation ?? '');
        $firstName  = ucfirst($object->first_name ?? '');
        $lastName   = ucfirst($object->last_name ?? '');
        $username   = ucfirst($object->username ?? '');
        $email      = $object->email;

        if (!empty($salutation) && !empty($firstName) && !empty($lastName)) {
            $name = sprintf("%s. %s %s", $salutation, $firstName, $lastName);
        }elseif (!empty($firstName) && !empty($lastName)) {
            $name = sprintf("%s %s", $firstName, $lastName);
        }elseif (!empty($firstName)) {
            $name = $firstName;
        }elseif (!empty($lastName)) {
            $name = $lastName;
        }elseif (!empty($username)) {
            $name = $username;
        }else{
            $name = $email;
        }

        if (!$substitute && (empty($firstName) && empty($lastName))) {
            return false;
        }

        return $name;
    }

    public function makeInitials(string $string): string
    {
        return Str::invoke()->generateInitials($string);
    }

    public function makeRandomUsername(string $firstName = "John", string $lastName = "Doe", int $randNo = 1000): string
    {
        $buildFullName = $firstName . " " . $lastName;
        $usernameParts = array_filter(explode(" ", strtolower($buildFullName))); //explode and lowercase name
        $usernameParts = array_slice($usernameParts, 0, 2); //return only first two array part

        $part1 = (!empty($usernameParts[0])) ? substr($usernameParts[0], 0, 8) : ""; //cut first name to 8 letters
        $part2 = (!empty($usernameParts[1])) ? substr($usernameParts[1], 0, 5) : ""; //cut second name to 5 letters
        $part3 = ($randNo) ? rand(0, $randNo) : "";

        $out = $part1 . str_shuffle($part2) . $part3; //str_shuffle to randomly shuffle all characters

        return strtolower(trim($out));
    }

    public function usernameFromEmail(string $email): string
    {
        // Split the username and domain from the email
        $parts = explode('@', $email);
        return strtolower(trim($parts[0]));
    }

}
