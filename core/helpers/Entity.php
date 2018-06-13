<?php
namespace App\Helpers;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

class Entity
{
    /**
     * Entity constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->hydrate($attributes);
    }

    /**
     * Hydrate an Entity with given properties (associative array), under the form propertyName => value
     * @param array $attributes - Associative array of properties, propertyName => value
     */
    public function hydrate(array $attributes): void
    {
        foreach ($attributes as $attributeName => $value) {
            if (property_exists(get_called_class(), $attributeName)) {
                $this->{$attributeName} = $value;
            }
        }
    }

    /**
     * Get rules concerning the validation of an entity
     * @return array - Associative array of rules to use for Entity validation
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Check entity rules and return errors if request does not match with them
     * @return array
     */
    public function validate()
    {
        $rules = $this->rules();
        $violations = [];
        $validator = Validation::createValidator();

        foreach ($rules as $property => $ruleValidation) {
            $violation = $validator->validate($this->{$property}, $ruleValidation);
            $violations[$property] = $violation;
        }

        $exportableViolations = [];
        // Exports errors from Symfony Validation Error Objects
        foreach ($violations as $property => $violationErrors) {
            foreach ($violationErrors as $violationError) {
                /**
                 * @var $violationError ConstraintViolation
                 */
                $exportableViolations[] = "$property: {$violationError->getMessage()}";
            }
        }

        return $exportableViolations;
    }
}