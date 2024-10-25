<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


final class FileUploader
{
    public function __construct(private readonly string $targetDirectory) {
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            throw new \Exception("Erreur upload du fichier");

        }
        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function delete(?string $filename,string $rep){
        if(null!=$filename){
            if(file_exists($rep.'/'.$filename)){
                unlink($rep.'/'.$filename);
            }
        }
    }
}