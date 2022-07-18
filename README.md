# Inactive

**📢 Note:** This repository is not maintained any more.

Correlation identifiers
=======================

Purpose: giving request/process correlation capabilities to your project.

This library provides a simple class that will contain three correlation
identifiers:

* One for the current process (see generator section below)
* One for the parent application that calls your application, if any. This value
 can be extracted from an HTTP header or provided manually
* One for the root application from which all the calls originate in the first
  place. This value can also be extracted from an HTTP header or provided
  manually

In other words, if we have three applications A, B and C and A calls B which in
turn calls application C, within application C, we'll have:

* the root correlation id coming from the process of application A
* the parent correlation id coming from the process of application B
* the current correlation id which is an identification of the current process
in application C

A little graph is worth a thousand words, so here is how this might look like:

```
+-------+  current: 3b5263fa-1644-4750-8f11-aaf61e58cd9e
| App A |  parent: NULL
+---+---+  root: NULL
    |
    |
    v
+-------+  current: 3fc044d9-90fa-4b50-b6d9-3423f567155f
| App B |  parent: 3b5263fa-1644-4750-8f11-aaf61e58cd9e
+---+---+  root: 3b5263fa-1644-4750-8f11-aaf61e58cd9e
    |
    |
    v
+-------+  current: 6a051d24-aa5b-4c57-bcb4-bbbb7eda1c16
| App C |  parent: 3fc044d9-90fa-4b50-b6d9-3423f567155f
+-------+  root: 3b5263fa-1644-4750-8f11-aaf61e58cd9e
```

Use cases
---------

1. You have multiple applications calling each other and you want to keep track
of which is calling which.
2. Your application produces logs and you want to know which logs come from the
same process.

Installation
------------

```bash
composer require manomano-tech/correlation-ids
```

Generator
---------

A generator is used to generate unique correlation ids for the current running
application.

This library provides one default generator (see [RamseyUuidGenerator])
but you can create your own by implementing the [UniqueIdGeneratorInterface].  

> **Note:** In order to use the [RamseyUuidGenerator] generator, you need to
> install the [ramsey/uuid] package.

[RamseyUuidGenerator]: /src/Generator/RamseyUuidGenerator.php
[UniqueIdGeneratorInterface]: /src/Generator/UniqueIdGeneratorInterface.php
[ramsey/uuid]: https://packagist.org/packages/ramsey/uuid

Usage
-----

You have two possibilities:

1. extracting correlation identifiers from HTTP headers
2. specifying parent and root correlation identifiers manually

### Extracting correlation identifiers from HTTP headers

```php
use ManomanoTech\CorrelationId\Factory\HeaderCorrelationIdContainerFactory;
use ManomanoTech\CorrelationId\Generator\RamseyUuidGenerator;
use ManomanoTech\CorrelationId\CorrelationEntryName;

// We specify which generator will be responsible for generating the
// identification of the current process
$generator = new RamseyUuidGenerator();

// We define what are the http header names to look for
$correlationEntryNames = new CorrelationEntryName(
    'Current-Correlation-id',
    'Parent-Correlation-id',
    'Root-Correlation-id'
);

$factory = new HeaderCorrelationIdContainerFactory(
    $generator,
    $correlationEntryNames
);
$correlationIdContainer = $factory->create(getallheaders());
```

### Specify parent and root correlation identifiers manually

```php
use ManomanoTech\CorrelationId\Factory\CorrelationIdContainerFactory;
use ManomanoTech\CorrelationId\Generator\RamseyUuidGenerator;
use ManomanoTech\CorrelationId\CorrelationEntryName;

// We specify which generator will be responsible for generating the
// identification of the current process
$generator = new RamseyUuidGenerator();

$factory = new CorrelationIdContainerFactory($generator);
$correlationIdContainer = $factory->create(
    '3fc044d9-90fa-4b50-b6d9-3423f567155f',
    '3b5263fa-1644-4750-8f11-aaf61e58cd9e'
);
```

> **Note:** parent and root correlation id may be null
