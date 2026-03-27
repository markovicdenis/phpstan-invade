<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

final class InvaderPropertyReflection implements PropertyReflection
{
    public function __construct(private PropertyReflection $propertyReflection)
    {
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->propertyReflection->getDeclaringClass();
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return $this->propertyReflection->getDocComment();
    }

    public function getReadableType(): Type
    {
        return $this->propertyReflection->getReadableType();
    }

    public function getWritableType(): Type
    {
        return $this->propertyReflection->getWritableType();
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return $this->propertyReflection->canChangeTypeAfterAssignment();
    }

    public function isReadable(): bool
    {
        return $this->propertyReflection->isReadable();
    }

    public function isWritable(): bool
    {
        return $this->propertyReflection->isWritable();
    }

    public function isDeprecated(): TrinaryLogic
    {
        return $this->propertyReflection->isDeprecated();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->propertyReflection->getDeprecatedDescription();
    }

    public function isInternal(): TrinaryLogic
    {
        return $this->propertyReflection->isInternal();
    }
}
