<?php

namespace App\Validators;

abstract class BaseValidator
{
    protected array $errors = [];

    abstract protected function rules(): array;

    public function validate(array $data, string $mode = 'create'): bool
    {
        $rules = $this->rules($mode);
        $this->errors = [];

        foreach ($rules as $field => $rule) {
            if (!$rule->validate($data[$field] ?? null)) {
                $label = ucfirst(str_replace('_', ' ', $field));
                $this->errors[$field] = "$label is invalid.";
            }
        }

        return $this->isValid();
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

