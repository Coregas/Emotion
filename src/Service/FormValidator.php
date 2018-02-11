<?php
namespace Emotion\Service;

class FormValidator
{

    private $rules = [];

    private $fields = [];

    private $errors = [];

    public function addRule ($fieldName, $message, $ruleType)
    {
        $this->rules[] = new ValidationRule($fieldName, $message, $ruleType);
    }

    public function addEntries($fields) {
        foreach ($fields as $fieldName => $value) {
            $this->fields[$fieldName] = $this->sanitize($value);
        }
    }


    public function getEntries() {
        return $this->fields;
    }

    public function validate() {
        foreach ($this->rules as $rule) {
            $this->testRule($rule);
        }
    }

    public function foundErrors()
    {
        if (count($this->errors)) {
            return true;
        }
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }


    public function asEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        if ($value == '') {
            return true;
        }
        return false;
    }

    public function sanitize($text)
    {
        $text = trim(strip_tags($text));
        if (get_magic_quotes_gpc()) {
            $text = stripslashes($text);
        }
        return $text;
    }

    /**
     * @return bool
     */
    public function isFormSubmited()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }

        return false;
    }
    private function testRule($rule)
    {
        if (isset($this->_errors[$rule->getFieldName()])) {
            return;
        }
        if (isset($this->fields[$rule->getFieldName()])) {
            $value = $this->fields[$rule->getFieldName()];
        }
        else {
            $value = null;
        }
        foreach (explode('|',$rule->getRuleType()) as $ruleType) {
            switch ($ruleType) {
                case 'required' :
                    if ($value == '') {
                        $this->errors[$rule->getFieldName()] = $rule->getMessage();
                        return;
                    }
                    break;
                    break;
                case 'email' :
                    if (!($this->asEmail($value))) {
                        $this->errors[$rule->getFieldName()] = $rule->getMessage();
                        return;
                    }
                    break;
                case 'name' :
                    if (!preg_match('/[^A-Za-z]/', $value)) {
                        $this->errors[$rule->getFieldName()] = $rule->getMessage();
                        return;
                    }
                    break;
                case 'birth_date' :
                    if (!$this->asBirthDate($value)) {
                        $this->errors[$rule->getFieldName()] = $rule->getMessage();
                        return;
                    }
                    break;
            }
        }
    }

    private function asBirthDate($value) {

        if ($value != '') {

            strtotime($value);

            return true;
        } else {
            return true;
        }
    }
}