<?php
declare(strict_types=1);

namespace App\Shop\Domain\Exception;

class ValidateCommandException extends \Exception
{
    public function __construct(
        string $message,
        private array $propertiesErrors,
    ) {}
    /**
     * @return array
     */
    public function getPropertiesErrors(): array
    {
        return $this->propertiesErrors;
    }


}
