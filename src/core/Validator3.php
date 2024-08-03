<?php
namespace Core;

class Validator3
{
    private $data;
    private $errors = [];
    private $rules = [
        'required' => 'validateRequired',
        'email' => 'validateEmail',
        'min' => 'validateMin',
        'max' => 'validateMax',
        'phone' => 'validatePhone',
        'gender' => 'validateGender',
        'cni' => 'validateCni',
        'char' => 'validateChar',
        'file' => 'validateFile'
    ];

    private $defaultMessages = [
        'required' => 'The :field field is required.',
        'email' => 'The :field field must be a valid email address.',
        'min' => 'The :field must be at least :param characters long.',
        'max' => 'The :field must be no more than :param characters long.',
        'phone' => 'The :field field must be a valid phone number.',
        'gender' => 'The :field field must be either "m" or "f".',
        'cni' => 'The :field field must be a valid CNI.',
        'char' => 'The :field field must not be empty.',
        'file' => 'The :field field must be a valid file type.'
    ];

    

    public function validate($field, $rules, $customMessages = [])
    {
        var_dump($field, true);
        $value = isset($this->data[$field]) ? $this->data[$field] : null;
        $rules = explode('|', $rules);

        foreach ($rules as $rule) {
            $ruleArgs = explode(':', $rule);
            $ruleName = $ruleArgs[0];
            $ruleParam = $ruleArgs[1] ?? null;

            if (isset($this->rules[$ruleName])) {
                $method = $this->rules[$ruleName];
                $message = $customMessages[$ruleName] ?? $this->defaultMessages[$ruleName];
                $message = str_replace(':field', $field, $message);
                $message = str_replace(':param', $ruleParam, $message);

                if (!$this->$method($value, $ruleParam)) {
                    $this->addError($field, $message);
                }
            }
        }
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
        Session::set("error", $message, $field);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    private function validateRequired($value)
    {
        var_dump("dsdsd",$value);
        var_dump(!empty($value));
        return !empty($value);
    }

    private function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateMin($value, $param)
    {
        return strlen($value) >= $param;
    }

    private function validateMax($value, $param)
    {
        return strlen($value) <= $param;
    }

    private function validatePhone($value)
    {
        return preg_match('/^(221|(\+)?221)?(77|78|76|70|75)\d{7}$/', $value);
    }

    private function validateGender($value)
    {
        $value = strtolower($value);
        return $value === 'm' || $value === 'f';
    }

    private function validateCni($value)
    {
        return strlen($value) === 13 && $this->isNumber($value);
    }

    private function validateChar($value)
    {
        return !empty(trim($value));
    }

    private function validateFile($value)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
        return in_array($extension, $allowedExtensions);
    }

    private function isNumber($num)
    {
        return preg_match('/^\d+$/', $num);
    }

    public static function validateData($data, $rules, $messages = [])
    {
        $validator = new self($data);
        var_dump($data);
        foreach ($rules as $field => $ruleString) {
            $validator->validate($field, $ruleString, $messages[$field] ?? []);
        }
        return $validator;
    }
}
