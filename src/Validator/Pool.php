<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

/**
 * Validator Pool
 */
class Pool
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @param ValidatorInterface[] $validators
     * @throws ValidatorException
     */
    public function __construct(
        array $validators = []
    ) {
        foreach ($validators as $validator) {
            if (!$validator instanceof ValidatorInterface) {
                throw new ValidatorException('Validator must implement ValidatorInterface.');
            }
        }

        $this->validators = $validators;
    }

    /**
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param string $validatorName
     * @return ValidatorInterface
     */
    public function getValidator(string $validatorName): ValidatorInterface
    {
        foreach ($this->validators as $name => $validator) {
            if ($name == $validatorName) {
                return $validator;
            }
        }

        throw new ValidatorException(sprintf('Invalid validator name %s.', $validatorName));
    }
}
