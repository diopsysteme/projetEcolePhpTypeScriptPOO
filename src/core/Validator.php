<?php

namespace Core;

use PDO;

class Validator
{
    private $data;
    private $errors = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($field, $rule, $message)
    {
        $value = isset($this->data[$field]) ? $this->data[$field] : null;

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'email':
                if (!$this->valideMail($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'min':
                if (strlen($value) < $message) {  // assuming $message contains the minimum length
                    $this->addError($field, "The $field must be at least $message characters long.");
                }
                break;

            case 'max':
                if (strlen($value) > $message) {  // assuming $message contains the maximum length
                    $this->addError($field, "The $field must be no more than $message characters long.");
                }
                break;

            // case 'unique':
            //     if ($this->isValueTaken($field, $value)) {  // custom method to check database for uniqueness
            //         $this->addError($field, $message);
            //     }
            //     break;

            case 'phone':
                if (!$this->valideNum($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'gender':
                if (!$this->valideSexe($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'cni':
                if (!$this->valideCni($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'char':
                if (!$this->valideChar($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'file':
                if (!$this->validateFile($value)) {
                    $this->addError($field, $message);
                }
                break;

            // Add more validation rules as needed
        }
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
        Session::set("error",$message,$field);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    // private function isValueTaken($field, $value)
    // {
    //     // Implement a method to check the database for unique fields
    //     // For example:
    //     $db = new MysqlDatabase();  // assuming MysqlDatabase is available
    //     $result = $db->query("SELECT COUNT(*) as count FROM users WHERE $field = :value", ['value' => $value]);
    //     return $result[0]['count'] > 0;
    // }

    private function valideMail($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function valideNum($num)
    {
       
        return preg_match('/^(221|(\+)?221)?(77|78|76|70|75)\d{7}$/', $num);
    }

    private function valideSexe($sexe)
    {
        $sexe = strtolower($sexe);

        return $sexe === 'm' || $sexe === 'f';
    }

    private function isNumber($num)
    {
        return preg_match('/^\d+$/', $num);
    }

    private function valideCni($cni)
    {
        return strlen($cni) === 13 && $this->isNumber($cni);
    }

    private function valideChar($char)
    {
        return !empty(trim($char));
    }

    private function validateFile($filePath, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'])
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return in_array($extension, $allowedExtensions);
    }

    public function validateData()
    {
        foreach ($this->data as $field => $value) {
            $this->validate($field, 'required', "The $field field is required.");

            if (strpos($field, 'mail') !== false && !array_key_exists($field, $this->errors)) {
                $this->validate($field, 'email', "The $field field must be a valid email address.");
            }

            if (strpos($field, 'telephone') !== false && !array_key_exists($field, $this->errors)) {
                $this->validate($field, 'phone', "The $field field must be a valid phone number.");
            }

            if (strpos($field, 'image') !== false ||
            strpos($field, 'photo') !== false
             &&!array_key_exists($field, $this->errors)) {
                $this->validate($field, 'file', "The $field field must be a valid image file.");
            }
        }
    }
}
