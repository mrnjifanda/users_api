<?php
namespace App\Helpers;
use App\App;

    class Validator {

        private $data;
        private $errors = [];
    
        public function __construct (array $data)
        {
            $this->data = $data;
        }

        private function getField (string $field): ?string
        {
            return $this->data[$field] ?? null;
        }

        private function verifyLenght (string $field, $min = 4, $max = 8): bool
        {
            return strlen($this->getField($field)) < $min || strlen($this->getField($field)) > $max;
        }

        public function explode (string $field, ?string $explode = '-'): ?array
        {
            return $this->getField($field) ? explode($explode, $this->getField($field)) : null;
        }

        public function isSimpleAlpha (string $field, ?string $errorMsg = 'Syntaxe incorrecte.'): void
        {
            if ($this->verifyLenght($field) || !preg_match('/^[a-zA-Z0-9]+$/', $this->getField($field))) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isAlpha (string $field, ?string $errorMsg = 'Syntaxe incorrecte.', ?int $min = 3, ?int $max = 20): void
        {
            if ($this->verifyLenght($field, $min, $max) || !preg_match('/^[a-zA-Z0-9ŸÜÛÙŒÔÏÎËÊÈÉÇÆÂÀÿüûùœôïîëêèéçæâà]+$/', $this->getField($field))) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isDate (string $field, ?string $errorMsg = 'Syntaxe de la date incorrecte.'): void
        {
            if ($this->verifyLenght($field, 5, 20) || (!preg_match("#(\d{4})/(\d{2})/(\d{2})#", $this->getField($field)) && !preg_match("#(\d{4})-(\d{2})-(\d{2})#", $this->getField($field)))) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isEmail (string $field, ?string $errorMsg = 'Syntaxe de l\'email incorrecte.'): void
        {
            if ($this->verifyLenght($field, 4, 40) || !filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isInt (string $field, ?string $errorMsg = 'Uniquement des chiffres.', ?int $min = 4, ?int $max = 4): void
        {
            if ($this->verifyLenght($field, $min, $max) || !is_numeric($this->getField($field))) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isUniq (App $app, string $field, string $table, string $champ, ?string $errorMsg = 'Cette occurrence existe déjà.', ?bool $delete = null): void
        {
            $deleteString = $delete ? " AND supprimer IS NULL" : null;
            $req = $app->fetch("SELECT id FROM $table WHERE $champ = :field $deleteString", ['field' => $this->getField($field)]);

            if($req){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isExit (App $app, string $field, string $table, string $champ, ?string $errorMsg = 'Cette occurrence n\'existe pas.', ?bool $delete = null): void
        {
            $deleteString = $delete ? "WHERE supprimer IS NULL" : null;
            $req = $app->fetch("SELECT id FROM $table WHERE $champ = :field", ['field' => $this->getField($field)]);

            if(!$req) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isUniqYear (string $field, App $app, string $table, string $champ, string $errorMsg, int $year): void
        {
            $req = $app->query("SELECT id FROM $table WHERE id_year = :id AND $champ = :field", ['id' => $year, 'field' => $this->getField($field)])->fetch();
            if($req){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isPassword (string $field, ?string $errorMsg = 'Mot de passe incorrect (4 à 40)'): void
        {
            if ($this->verifyLenght($field, 4, 41)) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isConfirmed (string $field, ?string $errorMsg = 'Confirmation incorrecte'): void
        {
            $value = $this->getField($field);
            if (empty($value) || $value != $this->getField($field . '-confirm')) {
                $this->errors[$field . '-confirm'] = $errorMsg;
            }
        }

        public function in_array (string $field, array $array, string $errorMsg): void
        {
            if (!in_array($this->getField($field), $array)) {
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isVaLid (): bool
        {
            return empty($this->errors);
        }

        public function getErrors (): array
        {
            return $this->errors;
        }
    }