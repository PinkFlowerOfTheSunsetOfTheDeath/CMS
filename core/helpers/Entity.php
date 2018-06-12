<?php
namespace App\Helpers;


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

    public function validate()
    {
        $rules = $this->rules();
        $violations = [];

//        $validator =
        foreach ($rules as $ruleName => $ruleValidations) {
//            $ruleViolations =
        }
        return $violations;
    }
}