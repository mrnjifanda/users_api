<?php
namespace App\Http;

class InvalidParameterException extends \Exception {

    public function __construct(string $name, int $type)
    {
        if ($type === Request::INT) {
            $type = 'entier';
        } else if ($type === Request::STRING) {
            $type = 'chasine de caractère';
        }
        parent::__construct("Le paramètre '$name' n'est pas du bon type, $type attendu");

        if ($type === Request::INT) {
           return (int) $_GET[$name];
        }

        return (string) $_GET[$name];
    }
}