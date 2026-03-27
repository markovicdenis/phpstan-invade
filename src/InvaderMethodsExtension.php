<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\ShouldNotHappenException;
use Spatie\Invade\Invader;

final class InvaderMethodsExtension implements MethodsClassReflectionExtension
{
    public function __construct(private InvadedTypeResolver $invadedTypeResolver)
    {
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (!$this->supports($classReflection)) {
            return false;
        }

        $invadedClassReflection = $this->invadedTypeResolver->resolveClassReflection($classReflection);

        if ($invadedClassReflection === null || !$invadedClassReflection->hasNativeMethod($methodName)) {
            return false;
        }

        return !$invadedClassReflection->getNativeMethod($methodName)->isStatic();
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        $invadedClassReflection = $this->invadedTypeResolver->resolveClassReflection($classReflection);

        if ($invadedClassReflection === null || !$invadedClassReflection->hasNativeMethod($methodName)) {
            throw new ShouldNotHappenException(sprintf('Cannot resolve invaded method %s::%s().', $classReflection->getName(), $methodName));
        }

        $methodReflection = $invadedClassReflection->getNativeMethod($methodName);

        if ($methodReflection->isStatic()) {
            throw new ShouldNotHappenException(sprintf('Static method %s::%s() is not exposed through Invader.', $invadedClassReflection->getName(), $methodName));
        }

        return new InvaderMethodReflection($methodReflection);
    }

    private function supports(ClassReflection $classReflection): bool
    {
        return $classReflection->getName() === Invader::class;
    }
}
