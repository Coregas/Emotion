<?php
namespace Emotion\Service;
use DateTime;

class FormValidator
{
    /**
     * @var array
     */
    private $rules = [];
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param $fieldName
     * @param $message
     * @param $ruleType
     */
    public function addRule ($fieldName, $message, $ruleType)
    {
        $this->rules[] = new ValidationRule($fieldName, $message, $ruleType);
    }

    /**
     * @param $fields
     */
    public function addEntries($fields) {
        foreach ($fields as $fieldName => $value) {
            $this->fields[$fieldName] = $this->sanitize($value);
        }
    }

    /**
     * @return array
     */
    public function getEntries() {
        return $this->fields;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getEntry($name) {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        return false;
    }


    public function validate() {
        foreach ($this->rules as $rule) {
            $this->testRule($rule);
        }
    }

    /**
     * @return bool
     */
    public function foundErrors()
    {
        if (count($this->errors)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $value
     * @return bool
     */
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

    /**
     * @param $text
     * @return string
     */
    public function sanitize($text)
    {
        $text = trim(strip_tags($text));
        if (get_magic_quotes_gpc()) {
            $text = stripslashes($text);
        }
        $text = $this->xssClean($text);
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

    /**
     * @param $rule
     */
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

                case 'email' :
                    if (!($this->asEmail($value))) {
                        $this->errors[$rule->getFieldName()] = $rule->getMessage();
                        return;
                    }
                    break;

                case 'name' :

                    if (preg_match('/[^A-Za-z]/', $value) != 0) {
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

    /**
     * @param $value
     * @return bool
     */
    private function asBirthDate($value) {

        if ($value != '') {

            $date = DateTime::createFromFormat('Y-m-d', $value);

            if (!$date) {
                return false;
            }
            $errors = DateTime::getLastErrors();
            if (!empty($errors['warning_count'])) {
                return false;
            }

            if ($date > new DateTime()) {
                return false;
            }

            return true;

        } else {
            return true;
        }
    }

    function xssClean($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
    }
}