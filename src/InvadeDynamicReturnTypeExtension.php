<?php

declare(strict_types=1);

namespace MarkovicDenis\PHPStanInvade;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Spatie\Invade\Invader;

final class InvadeDynamicReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return $functionReflection->getName() === 'invade';
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): Type
    {
        $arg = $functionCall->getArgs()[0] ?? null;

        if ($arg === null) {
            return new GenericObjectType(Invader::class, [new MixedType()]);
        }

        return new GenericObjectType(Invader::class, [$scope->getType($arg->value)]);
    }
}
