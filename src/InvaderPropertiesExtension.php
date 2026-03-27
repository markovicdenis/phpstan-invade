<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\ShouldNotHappenException;
use Spatie\Invade\Invader;

final class InvaderPropertiesExtension implements PropertiesClassReflectionExtension
{
    public function __construct(private InvadedTypeResolver $invadedTypeResolver)
    {
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (!$this->supports($classReflection)) {
            return false;
        }

        $invadedClassReflection = $this->invadedTypeResolver->resolveClassReflection($classReflection);

        if ($invadedClassReflection === null || !$invadedClassReflection->hasNativeProperty($propertyName)) {
            return false;
        }

        return !$invadedClassReflection->getNativeProperty($propertyName)->isStatic();
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $invadedClassReflection = $this->invadedTypeResolver->resolveClassReflection($classReflection);

        if ($invadedClassReflection === null || !$invadedClassReflection->hasNativeProperty($propertyName)) {
            throw new ShouldNotHappenException(sprintf('Cannot resolve invaded property %s::$%s.', $classReflection->getName(), $propertyName));
        }

        $propertyReflection = $invadedClassReflection->getNativeProperty($propertyName);

        if ($propertyReflection->isStatic()) {
            throw new ShouldNotHappenException(sprintf('Static property %s::$%s is not exposed through Invader.', $invadedClassReflection->getName(), $propertyName));
        }

        return new InvaderPropertyReflection($propertyReflection);
    }

    private function supports(ClassReflection $classReflection): bool
    {
        return $classReflection->getName() === Invader::class;
    }
}
