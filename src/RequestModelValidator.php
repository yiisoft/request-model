<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Validator\Rules;

final class RequestModelValidator
{
    public function validate(ValidatableModelInterface $model, array $data): array
    {
        $rules = $this->prepareRules(
            $data,
            $model->getRules(),
            $model->getOptionalFields(),
            $model instanceof StrictValidatableModelInterface
        );

        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $result = (new Rules($fieldRules))->validate(ArrayHelper::getValueByPath($data, $field));
            if ($result->isValid() === false) {
                $errors[$field] = $result->getErrors();
            }
        }

        return $errors;
    }

    private function prepareRules(array $data, array $allRules, array $optionalFields, bool $strict): array
    {
        $rules = [];
        foreach ($allRules as $field => $fieldRules) {
            if ($this->useRule($field, $data, $optionalFields, $strict)) {
                $rules[$field] = $fieldRules;
            }
        }
        return $rules;
    }

    private function useRule(string $field, array $data, array $optionalFields, bool $strict): bool
    {
        if (!in_array($field, $optionalFields)) {
            return true;
        }

        return ($strict && ArrayHelper::pathExists($data, $field)) ||
            (!$strict && !empty(ArrayHelper::getValueByPath($data, $field)));
    }
}
