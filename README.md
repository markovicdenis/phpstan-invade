# phpstan-invade

`phpstan-invade` is a small PHPStan extension package for `spatie/invade`.

It teaches PHPStan that `Spatie\Invade\Invader<T>` exposes the instance properties and methods of `T`, including members that would otherwise be reported as inaccessible.

## Why this package exists

Using only a dynamic return type extension is not enough for `Invader<T>`. PHPStan first has to understand that the wrapped members exist on the invader object, and then it has to stop treating those members as inaccessible.

This package solves that with:

- a methods class reflection extension
- a properties class reflection extension
- wrapper reflections that mark invaded members as publicly accessible to the analyser

## Installation

```bash
composer require --dev markovicdenis/phpstan-invade phpstan/extension-installer
```

If you do not use `phpstan/extension-installer`, include the package config manually:

```neon
includes:
    - vendor/markovicdenis/phpstan-invade/phpstan-invade.neon
```

## Example

```php
$invaded = invade($affiliate);

$invaded->user_id;
$invaded->type;
$invaded->privateMethod();
```

With the extension loaded, PHPStan will resolve those members from the invaded object instead of reporting missing or inaccessible member errors on `Spatie\Invade\Invader`.

## Package contents

- `phpstan-invade.neon` registers the PHPStan services.
- `src/InvaderMethodsExtension.php` resolves invaded instance methods.
- `src/InvaderPropertiesExtension.php` resolves invaded instance properties.
- `src/InvaderMethodReflection.php` and `src/InvaderPropertyReflection.php` expose those members as accessible to the analyser.
