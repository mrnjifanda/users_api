<?php

namespace App\Helpers;

class Upload
{    
    /**
     * upload
     *
     * @param  array $file ['name, 'size', 'tmp_name']
     * @param  string $return_name
     * @param  string $return_paph
     * @param  array $extension_valides ['jpg', 'jpeg', 'gif', 'png']
     * @param  int $taille_max
     * @return null|string
     */
    public static function upload (array $file, string $return_name, string $return_paph, array $extension_valides = ['jpg', 'jpeg', 'gif', 'png'],  int $taille_max = 10485760): ?string
    {
        if (!isset($file['name']) || !isset($file['size']) || !isset($file['tmp_name'])) { return 'Fichier invalide'; }

        if ($file['size'] <= $taille_max) {

            $extension_upload = strtolower(substr(strrchr($file['name'], '.'), 1));

            if (in_array($extension_upload, $extension_valides)) {

                $chemin = $return_paph . "/" . $return_name . "." . $extension_upload;
                $resultat = move_uploaded_file($file['tmp_name'], $chemin);

                if ($resultat) { return null; }

                return 'Erreur lors du téléchargement';
            }

            return 'Fichier non supporté (' . implode(', ', $extension_valides) .')';
        }

        return 'Taille du fichier supérieure a la limite';
    }
}