<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use ReflectionProperty;

class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @var array
     */
    private $propertyCache;

    public function createFromApiResult($apiResult, AbstractResource $resource)
    {
        $this->fillAttributes($apiResult, $resource);
        $this->fillAttributes($apiResult->attributes, $resource);

        return $resource;
    }

    private function fillAttributes(object $data, AbstractResource $resource): void
    {
        foreach ($data as $property => $value) {
            if (!self::hasProperty($resource, $property)) {
                continue;
            }

            $type = self::getPropertyType($resource, $property);

            if (($type === '\DateTime' || $type === '\DateTime|null') && $value !== null) {
                try {
                    $value = new \DateTime($value);
                } catch (\Exception $e) {
                    continue;
                }
            }

            if ($type === 'bool') {
                $value = (bool) $value;
            }

            if ($type === 'int') {
                $value = (int) $value;
            }

            $resource->{$property} = $value;
        }
    }

    private function hasProperty(object $instance, string $property): bool
    {
        $className = get_class($instance);

        return property_exists($className, $property);
    }

    private function getPropertyType(object $instance, string $property): string
    {
        $className = get_class($instance);

        if (isset($this->propertyCache[$className][$property])) {
            return $this->propertyCache[$className][$property];
        }

        $propertyType = 'string';

        try {
            $propertyReflection = new ReflectionProperty(get_class($instance), $property);
            $docComment = $propertyReflection->getDocComment();
            if ($docComment !== false && preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
                $propertyType = $matches[1];
            }
        } catch (\ReflectionException $e) {
            return 'string';
        }

        $this->propertyCache[$className][$property] = $propertyType;

        return $propertyType;
    }
}
