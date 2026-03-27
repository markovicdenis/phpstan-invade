<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;

final class InvadedTypeResolver
{
    public function __construct(private ReflectionProvider $reflectionProvider)
    {
    }

    public function resolveType(ClassReflection $classReflection): ?Type
    {
        return $classReflection->getActiveTemplateTypeMap()->getType('T');
    }

    public function resolveClassReflection(ClassReflection $classReflection): ?ClassReflection
    {
        $invadedType = $this->resolveType($classReflection);

        if ($invadedType === null) {
            return null;
        }

        $classNames = $invadedType->getObjectClassNames();

        if ($classNames === []) {
            return null;
        }

        $className = $classNames[0];

        if (!$this->reflectionProvider->hasClass($className)) {
            return null;
        }

        return $this->reflectionProvider->getClass($className);
    }
}
