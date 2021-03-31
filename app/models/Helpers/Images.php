<?php

namespace App\Helpers;

class Images
{
    public static function miniature ($image, $chemin, $nom, ?int $NouvelleLargeur = 100, ?int $NouvelleHauteur = 100): bool
    {
        $infoImages = getimagesize($image);
        $NouvelleImage = ImageCreateTrueColor($NouvelleLargeur, $NouvelleHauteur);
        $file_extension = substr(strtolower($image), -4);
        $valides_extensions = [".jpeg", '.jpg', '.png', '.gif'];

        if (!in_array($file_extension, $valides_extensions)) {
            return false;
        }

        if ($file_extension == ".jpg" || $file_extension == ".jpeg") {
            $ImageChoisie = ImageCreateFromJpeg($image);
        }
        
        if ($file_extension == ".png") {
            $ImageChoisie = ImageCreateFromPng($image);
            imagealphablending($NouvelleImage, false);
            imagesavealpha($NouvelleImage, true);
        }

        if ($file_extension == ".gif") {
            $ImageChoisie = ImageCreateFromGif($image);
        }

        ImageCopyResampled($NouvelleImage, $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $infoImages[0], $infoImages[1]);

        switch ($file_extension) {
            case ".jpg":
                imagejpeg($NouvelleImage, $chemin . "/" . $nom . $file_extension, 90);
                break;

            case ".jpeg":
                imagejpeg($NouvelleImage, $chemin . "/" . $nom . $file_extension, 90);
                break;

            case ".png":
                imagepng($NouvelleImage, $chemin . "/" . $nom . $file_extension);
                break;

            case ".gif":
                imagegif($NouvelleImage, $chemin . "/" . $nom . $file_extension);
                break;
        }

        imagedestroy($ImageChoisie);
        imagedestroy($NouvelleImage);
        return true;
    }
}