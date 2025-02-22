<?php

declare (strict_types=1);
namespace WP_Ultimo\Dependencies;

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use WP_Ultimo\Dependencies\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (ContainerConfigurator $containerConfigurator) : void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
    // Define what rule sets will be applied
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_81);
    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    // register a single rule
    $services->set(TypedPropertyRector::class);
};
