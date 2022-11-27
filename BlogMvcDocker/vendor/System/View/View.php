<?php

namespace System\View;

use System\File;

class View implements ViewInterface
{
     /**
     *
     * @var \System\File
     */
    private $file;

     /**
     *
     * @var string
     */
    private $viewPath;

     /**
     *
     * @var array
     */
    private $data = [];

     /**
     *
     * @var string
     */
    private $output;

     /**
     *
     * @param \System\File $app
     * @param string $viewPath
     * @param array $data
     */
    public function __construct(File $file, $viewPath, array $data)
    {
        $this->file = $file;

        $this->preparePath($viewPath);

        $this->data = $data;
    }

    /**
    *
    * @param string $viewPath
    * @return void
    */
    private function preparePath($viewPath)
    {
        $relativeViewPath = 'App/Views/' . $viewPath . '.php';

        $this->viewPath = $this->file->to($relativeViewPath);

        if (! $this->viewFileExists($relativeViewPath)) {
            die('<b>' . $viewPath . ' View</b>' . ' does not exists in Views Folder');
        }
    }

    /**
    *
    * @param string $viewPath
    * @return bool
    */
    private function viewFileExists($viewPath)
    {
        return $this->file->exists($viewPath);
    }

    /**
    * {@inheritDoc}
    */
    public function getOutput()
    {
        if (is_null($this->output)) {
            ob_start();

            extract($this->data);

            require $this->viewPath;

            $this->output = ob_get_clean();
        }

        return $this->output;
    }

    /**
    * {@inheritDoc}
    */
    public function __toString()
    {
        return $this->getOutput();
    }
}