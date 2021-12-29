<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

final class Name
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function generateFullName($obj, $substitute = false){

        $salutation = ucfirst($obj->salutation);
        $firstName  = ucfirst($obj->first_name);
        $lastName   = ucfirst($obj->last_name);
        $username   = ucfirst($obj->username);
        $email      = $obj->email;

        if (!empty($salutation) && !empty($firstName) && !empty($lastName)) {
            $name = sprintf("%s. %s %s", ucwords($obj->salutation), ucwords($obj->first_name), ucwords($obj->last_name));
        }elseif (!empty($firstName) && !empty($lastName)) {
            $name = sprintf("%s %s", ucwords($obj->first_name), ucwords($obj->last_name));
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

    public function generateInitials(string $string): string
    {
        return Str::invoke()->generateInitials($string);
    }

    public function randomUsername(string $firstName = "John", string $lastName = "Doe", int $randNo = 1000)
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