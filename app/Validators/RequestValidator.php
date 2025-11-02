<?php

namespace App\Validators;

use Respect\Validation\Validator as Validator;

class RequestValidator extends BaseValidator
{
    protected function rules($mode = 'create'): array
    {
        return [
            'start_date' => Validator::notEmpty()->date(),
            'end_date'   => Validator::notEmpty()->date(),
            'reason'     => Validator::notEmpty()
        ];
    }

    public function validate(array $data, string $mode = 'create'): bool
    {
        parent::validate($data);

        if (empty($this->errors['start_date']) && empty($this->errors['end_date'])) {

            $start = strtotime($data['start_date']);
            $end   = strtotime($data['end_date']);
            $today = strtotime('today');

            // Rule 1: start_date must not be in the past
            if ($start < $today) {
                $this->errors['start_date'][] = 'Start date cannot be in the past.';
            }

            // Rule 2: end_date must be after start_date
            if ($end <= $start) {
                $this->errors['end_date'][] = 'End date must be after start date.';
            }
        }

        return $this->isValid();
    }
}