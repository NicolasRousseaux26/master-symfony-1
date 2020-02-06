<?php

/*
 * This file is part of the master-symfony package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * $fileName = $this->uploader->upload($file);
     */
    public function upload(UploadedFile $image)
    {
        // Génére le nom de l'image
        $fileName = uniqid().'.'.$image->guessExtension();
        // Déplace l'image
        $image->move($this->uploadDir, $fileName);

        return $fileName;
    }

    public function remove($fileName)
    {
        $fs = new Filesystem();
        // Récupèrer le chemin du fichier
        $file = $this->uploadDir.'/'.$fileName;
        if ($fs->exists($file)) {
            // Supprimer le fichier
            $fs->remove($file);
        }
    }
}
