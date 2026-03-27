<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

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

        if (!$invadedType instanceof TypeWithClassName) {
            return null;
        }

        if (!$this->reflectionProvider->hasClass($invadedType->getClassName())) {
            return null;
        }

        return $this->reflectionProvider->getClass($invadedType->getClassName());
    }
}
