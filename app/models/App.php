<?php
namespace App;

use \PDO;

class App
{
    private $host = '188.121.44.72';
    private $username = '';
    private $password = '';
    private $database = '';

    private $pdo;
    private $isDev;

    public function __construct (bool $isDev = false)
    {
        $this->isDev = $isDev;
        if ($this->isDev) {
            $this->host = 'localhost';
            $this->username = 'root';
            $this->password = 'root';
            $this->database = 'users_api';
        }
    }

    public function getIsDev(): bool
    {
        return $this->isDev;
    }

    /** PDO **/
    public function getPDO (): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO('mysql:dbname=' . $this->database . ';charset=utf8;host=' . $this->host, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
        return $this->pdo;
    }

    public function lastId (): int
    {
        return $this->getPDO()->lastInsertId();
    }

    public function query (string $query, ?array $params = [])
    {
        $req = $this->getPDO()->prepare($query);
        $req->execute($params);
        return $req;
    }

    public function fetch (string $query, ?array $params = [], ?string $classMaping = null)
    {
        $req = $this->query($query, $params);
        $fetch = $classMaping ? $req->fetchObject($classMaping) : $req->fetch(PDO::FETCH_OBJ);
        return $fetch ? $fetch : null;
    }

    public function fetchAll (string $query, ?array $params = [], ?string $classMaping = null)
    {
        $req = $this->query($query, $params);
        $fetch = $classMaping ? $req->fetchAll(PDO::FETCH_CLASS, $classMaping) : $req->fetchAll(PDO::FETCH_OBJ);
        return $fetch ? $fetch : null;
    }

    public function selects (string $table, string $classMaping, string $select = '*', string $delete = 'NULL')
    {
        return $this->fetchAll("SELECT $select FROM $table WHERE supprimer IS $delete", null, $classMaping);
    }

    public function fetchArray (string $query, ?array $params = []): ?array
    {
        $req = $this->query($query, $params)->fetchAll();
        return $req ? $req : null;
    }

    public function count (string $query, ?array $params = []): ?int
    {
        $count = $this->query($query, $params)->fetch(PDO::FETCH_NUM)[0];
        return $count ? $count : 0;
    }

    /** HEADER **/
    public function http_codes (int $code): ?string
    {
        $codes = [
            200 => 'ok',
            204 => 'No Content',
            301 => 'Moved Permanently',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error'
        ];
        return $codes[$code] ?? null;
    }

    public function http_status (int $code): void
    {
        $message = $this->http_codes($code);
        if ($message) { header('HTTP/1.0 ' . $code . ' ' . $message, true, $code); }
    }
}