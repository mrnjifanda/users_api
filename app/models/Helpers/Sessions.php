<?php

namespace App\Helpers;

class Sessions
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function write($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function read($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function unset($key)
    {
        if ($this->read($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function setFlash(string $key, string $message): void
    {
        $newFlash = ["type" => $key, "message" => $message];

        if ($this->hasFlashes()) {
            array_push($_SESSION['flash'], $newFlash);
        } else {
            $_SESSION['flash'] = [
                $newFlash
            ];
            // $_SESSION['flash'][$key] = $message;
        }
    }

    public function hasFlashes(): bool
    {
        return isset($_SESSION['flash']);
    }

    public function getFlashes()
    {
        $flash = $this->read('flash');
        $this->unset('flash');

        return $flash;
    }
}
