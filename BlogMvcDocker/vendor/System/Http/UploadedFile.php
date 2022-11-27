<?php

namespace System\Http;

use System\Application;

class UploadedFile
{
     /**
     *
     * @var \System\Application
     */
    private $app;

     /**
     *
     * @var array
     */
    private $file = [];

     /**
     *
     * @var string
     */
    private $fileName;

     /**
     *
     * @var string
     */
    private $nameOnly;

     /**
     *
     * @var string
     */
    private $extension;

     /**
     *
     * @var string
     */
    private $mimeType;

     /**
     *
     * @var string
     */
    private $tempFile;

     /**
     *
     * @var int
     */
    private $size;

     /**
     *
     * @var int
     */
    private $error;

     /**
     *
     * @var array
     */
    private $allowedImageExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

     /**
     *
     * @param string $input
     */
    public function __construct($input)
    {
        $this->getFileInfo($input);
    }

     /**
     *
     * @param string $input
     * @return void
     */
    private function getFileInfo($input)
    {
        if (empty($_FILES[$input])) {
            return;
        }

        $file = $_FILES[$input];

        $this->error = $file['error'];

        if ($this->error != UPLOAD_ERR_OK) {
            return;
        }

        $this->file = $file;

        $this->fileName = $this->file['name'];

        $fileNameInfo = pathinfo($this->fileName);

        $this->nameOnly = $fileNameInfo['basename'];

        $this->extension = strtolower($fileNameInfo['extension']);

        $this->mimeType = $this->file['type'];

        $this->tempFile = $this->file['tmp_name'];

        $this->size = $this->file['size'];
    }

     /**
     *
     * @return bool
     */
    public function exists()
    {
        return ! empty($this->file);
    }

     /**
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

     /**
     *
     * @return string
     */
    public function getNameOnly()
    {
        return $this->nameOnly;
    }

     /**
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

     /**
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

     /**
     *
     * @return bool
     */
    public function isImage()
    {
        return strpos($this->mimeType, 'image/') === 0 AND
               in_array($this->extension, $this->allowedImageExtensions);
    }

     /**
     *
     * @param string $target
     * @param string $newFileName
     * @return string
     */
    public function moveTo($target, $newFileName = null)
    {
        $fileName = $newFileName ?: sha1(mt_rand()) . '_' . sha1(mt_rand());

        $fileName .= '.' .$this->extension;

        if (! is_dir($target)) {
            mkdir($target, 0777, true);
        }

        $uploadedFilePath = rtrim($target , '/') . '/' . $fileName;

        move_uploaded_file($this->tempFile, $uploadedFilePath);

        return $fileName;
    }
}