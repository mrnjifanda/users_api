<?php
namespace App\Helpers;

class Cookies
{
    public function read (?string $key = 'remember')
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    public function write (?string $key = 'remember', ?string $value = null, int $date)
    {
        setcookie($key, $value, time() + $date);
    }

    public function delete (?string $key = 'remember')
    {
        if ($this->read($key)) setcookie($key, null, -1);
    }
}