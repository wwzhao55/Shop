<?php
namespace App\libraries\Model;

/**
 * Validation Trait that provides methods to create and update model easily
 */
trait Validation
{
    private $_errors;
    private $_validation;
    protected $originalFillable = [];

    public static function createFromRequest($input = [], $options = [])
    {
        $model = new static();
        return $model->saveFromRequest($input, $options) ? $model : null;
    }

    public function updateFromRequest($input = [], $options = [])
    {
        return $this->saveFromRequest($input, $options);
    }

    public function saveFromRequest($input = [], $options = [])
    {
        
        if (!$this->validateRequest($input, $options)) {
            return false;
        }

        $this->fill($input);
        $this->fillable = $this->originalFillable;

        if (method_exists($this, 'beforeSave')) {
            if (!$this->beforeSave($options)) {
                return false;
            }
        }
        $beforeExists = $this->exists;
        $saved = $this->save();
        $afterExists = $this->exists;

        if ($saved && method_exists($this, 'afterSave')) {
            $this->exists = $beforeExists;
            $this->afterSave($options);
            $this->exists = $afterExists;
        }

        return $saved;
    }

    public function validateRequest(&$input, $options = [])
    {
        
        if (method_exists($this, 'beforeValidate')) {
            if (!$this->beforeValidate($input, $options)) {
                return false;
            }
        }

        $rules = method_exists($this, 'rules') ? $this->rules() : [];
        $ruleMessages = method_exists($this, 'ruleMessages') ? $this->ruleMessages() : [];
        $keys = [];

        if (!empty($rules)) {
            $this->_validation = \Validator::make($input, $rules, $ruleMessages);
            $errors = $this->_validation->errors();
            $errorMessages = $errors->getMessages();

            foreach ($errorMessages as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }
            foreach ($rules as $key => $rs) {
                if (!array_key_exists($key, $errorMessages)) {
                    $keys[] = $key;
                }
            }
        }

        foreach ($input as $key => $value) {
            $validator = 'validator' . ucfirst($key);
            if (!method_exists($this, $validator)) {
                continue;
            }
            if ($this->$validator($value, $input, $options)) {
                $keys[] = $key;
            }
        }
        $errors = $this->getErrors(['raw' => true]);
        $pass = !$errors || $errors->isEmpty();

        if (!$pass && !array_get($options, 'ignore')) {
            return false;
        }
        $this->originalFillable = $this->fillable;
        $this->fillable = array_unique(array_merge($this->fillable, $keys));

        if (method_exists($this, 'afterValidate')) {
            $this->afterValidate($input, $options);
        }

        return true;
    }

    public function addError($key, $message)
    {
        if ($this->_errors === null) {
            $this->_errors = new \Illuminate\Support\MessageBag();
        }

        $this->_errors->add($key, $message);
    }

    public function getValidations()
    {
        return $this->_validation;
    }

    public function getErrors($options = [])
    {
        if (array_get($options, 'raw')) {
            return $this->_errors;
        }
        if ($this->_errors === null) {
            return [];
        }
        return $this->_errors->getMessages();
    }

    public function getError()
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return;
        }
        $errors = reset($errors);
        return empty($errors) ? null : reset($errors);
    }

    public function hasErrors()
    {
        $errors = $this->getErrors(['raw' => true]);
        return $errors && !$errors->isEmpty();
    }
}