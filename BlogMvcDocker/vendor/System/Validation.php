<?php

namespace System;

class Validation
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
    private $errors = [];

     /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

     /**
     *
     * @param string $inputName
     * @param string $customErrorMessage
     * @return $this
     */
    public function required($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputName);

        if ($inputValue === '') {
            $message = $customErrorMessage ?: sprintf('%s est requis', ucfirst($inputName));
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param string $customErrorMessage
     * @return $this
     */
    public function requiredFile($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $file = $this->app->request->file($inputName);

        if (! $file->exists()) {
            $message = $customErrorMessage ?: sprintf('%s est requis', ucfirst($inputName));
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param string $customErrorMessage
     * @return $this
     */
    public function image($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $file = $this->app->request->file($inputName);

        if (! $file->exists()) {
            return $this;
        }

        if (! $file->isImage()) {
            $message = $customErrorMessage ?: sprintf('%s l\imagel n\est pas valide', ucfirst($inputName));
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param string $customErrorMessage
     * @return $this
     */
    public function email($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputName);

        if (! filter_var($inputValue, FILTER_VALIDATE_EMAIL)) {
            $message = $customErrorMessage ?: sprintf('%s l\email n\est pas valide', ucfirst($inputName));
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param string $customErrorMessage
     * @return $this
     */
    public function float($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputName);

        if (! is_float($inputValue)) {
            $message = $customErrorMessage ?: sprintf('%s Accepte seuklement les floats', ucfirst($inputName));
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param int $length
     * @param string $customErrorMessage
     * @return $this
     */
    public function minLen($inputName, $length, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputName);

        if (strlen($inputValue) < $length) {
            $message = $customErrorMessage ?: sprintf('%s doit être moins %d', ucfirst($inputName), $length);
            $this->addError($inputName, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param int $length
     * @param string $customErrorMessage
     * @return $this
     */
    public function maxLen($inputName, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputValue);

        if (strlen($inputValue) > $length) {
            $message = $customErrorMessage ?: sprintf('%s doit etre plus grand %d', ucfirst($inputName), $length);
            $this->addError($inputName, $message);
        }

        return $this;

    }

     /**
     *
     * @param string $fistInput
     * @param string $secondInput
     * @param string $customErrorMessage
     * @return $this
     */
    public function match($firstInput, $secondInput, $customErrorMessage = null)
    {
        $firstInputValue = $this->value($firstInput);
        $secondInputValue = $this->value($secondInput);

        if ($firstInputValue != $secondInputValue) {
            $message = $customErrorMessage ?: sprintf('%s doit correspondre %s', ucfirst($secondInput), ucfirst($firstInput));
            $this->addError($secondInput, $message);
        }

        return $this;
    }

     /**
     *
     * @param string $inputName
     * @param array $databaseData
     * @param string $customErrorMessage
     * @return $this
     */
    public function unique($inputName, array $databaseData, $customErrorMessage = null)
    {
        if ($this->hasErrors($inputName)) {
            return $this;
        }

        $inputValue = $this->value($inputName);

        $table = null;
        $column = null;
        $exceptionColumn = null;
        $exceptionColumnValue = null;

        if (count($databaseData) == 2) {
            list($table, $column) = $databaseData;
        } elseif (count($databaseData == 4)) {
            list($table, $column, $exceptionColumn, $exceptionColumnValue) = $databaseData;
        }

        if ($exceptionColumn AND $exceptionColumnValue) {
            $result = $this->app->db->select($column)
                                    ->from($table)
                                    ->where($column . ' = ? AND ' . $exceptionColumn . ' != ?' , $inputValue, $exceptionColumnValue)
                                    ->fetch();
        } else {
            $result = $this->app->db->select($column)
                                    ->from($table)
                                    ->where($column . ' = ?' , $inputValue)
                                    ->fetch();
        }

        if ($result) {
            $message = $customErrorMessage ?: sprintf('%s existe deja', ucfirst($inputName));
            $this->addError($inputName, $message);
        }
    }

     /**
     *
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->errors[] = $message;

        return $this;
    }

     /**
     *
     * @return bool
     */
    public function fails()
    {
        return ! empty($this->errors);
    }

     /**
     *
     * @return bool
     */
    public function passes()
    {
        return empty($this->errors);
    }

     /**
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->errors;
    }

     /**
     *
     * @return string
     */
    public function flattenMessages()
    {
        return implode('<br>', $this->errors);
    }

     /**
     *
     * @param string $input
     * @return mixed
     */
    private function value($input)
    {
        return $this->app->request->post($input);
    }

     /**
     *
     * @param string $inputName
     * @param string $errorMessage
     * @return void
     */
    private function addError($inputName, $errorMessage)
    {
        $this->errors[$inputName] = $errorMessage;
    }

     /**
     *
     * @param string $inputName
     */
    private function hasErrors($inputName)
    {
        return array_key_exists($inputName, $this->errors);
    }
}