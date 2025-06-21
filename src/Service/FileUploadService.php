<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    protected SluggerInterface $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    /**
     * @param UploadedFile $uploadedFile
     * @param string $directory
     * @return string
     */
    public function renameUploadedFile(UploadedFile $uploadedFile, string $directory): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($directory, $newFilename);

        return $newFilename;
    }

}