<?php


namespace App\Services;


use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ValidationService
{
    public function __construct()
    {
    }

    /**
     * @param array $variable
     * @param $constraint
     * @return array
     */
    public function validateConstraint(array $variable, $constraint)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($variable, $constraint);

        if ($violations->count() > 0) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $propertyName = str_replace('[','',$violation->getPropertyPath());
                $propertyName = str_replace(']','',$propertyName);
                $errorMessages[$propertyName] = $violation->getConstraint()->message;
            }
            return $errorMessages;
        }
    }

    public function validationVariableNotBlank($variables)
    {
        $errors = [];
        foreach ($variables as $key => $variable){
            $var = [
                $key => $variable
            ];
            $constraint = new Assert\Collection([
                $key => new Assert\NotBlank(['message' => "Le champ $key ne peut pas être vide"])
            ]);

            if($this->validateConstraint($var, $constraint) != null){
                $errors = $this->validateConstraint($var, $constraint);
            }
        }
        return $errors;
    }
}
