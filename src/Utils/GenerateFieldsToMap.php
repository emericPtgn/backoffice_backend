<?php

namespace App\Utils;

use ReflectionClass;

class GenerateFieldsToMap {
    public static function getPropertiesAndSetters($document) {
        $reflector = new ReflectionClass($document);
        $properties = $reflector->getProperties();
        $result = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $setterName = 'set' . ucfirst($propertyName);

            if ($reflector->hasMethod($setterName)) {
                $result[$propertyName] = $setterName;
            }
        }

        return $result;
    }
}
