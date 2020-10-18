<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Validator\Rules;

final class RequestModelValidator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $result = (new Rules($fieldRules))->validate(ArrayHelper::getValueByPath($data, $field));
            if ($result->isValid() === false) {
                $errors[$field] = $result->getErrors();
            }
        }

        return $errors;
    }
}
