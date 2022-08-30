<?php

namespace App\Shop\Infrastructure\Tools;

use App\Shop\Application\Command\MessageInterface;
use App\Shop\Domain\Exception\ValidateCommandException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidateCommandTrait
{
    private ValidatorInterface $validator;

    /**
     * @throws ValidateCommandException
     */
    public function validate(MessageInterface $command) {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidateCommandException('Command Validation failed',  $this->getErrorMessagesFromValidationResult($errors));
        }
    }

    private function getErrorMessagesFromValidationResult(ConstraintViolationList $errors): array {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
        }

        return $errorMessages;
    }
}
