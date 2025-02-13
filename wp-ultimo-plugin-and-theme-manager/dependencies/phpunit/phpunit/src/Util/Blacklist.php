<?php

declare (strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

use const DIRECTORY_SEPARATOR;
use function class_exists;
use function defined;
use function dirname;
use function strpos;
use function sys_get_temp_dir;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Composer\Autoload\ClassLoader;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\DeepCopy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Instantiator\Instantiator;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version as PharIoVersion;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Project;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type;
use PHPUnit\Framework\TestCase;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophet;
use ReflectionClass;
use ReflectionException;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeUnitReverseLookup\Wizard;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Diff\Diff;
use SebastianBergmann\Environment\Runtime;
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\FileIterator\Facade as FileIteratorFacade;
use SebastianBergmann\GlobalState\Snapshot;
use SebastianBergmann\Invoker\Invoker;
use SebastianBergmann\ObjectEnumerator\Enumerator;
use SebastianBergmann\RecursionContext\Context;
use SebastianBergmann\ResourceOperations\ResourceOperations;
use SebastianBergmann\Timer\Timer;
use SebastianBergmann\Type\TypeName;
use SebastianBergmann\Version;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Text_Template;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Tokenizer;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class Blacklist
{
    /**
     * @var array<string,int>
     */
    public static $blacklistedClassNames = [
        // composer
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Composer\Autoload\ClassLoader::class => 1,
        // doctrine/instantiator
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Instantiator\Instantiator::class => 1,
        // myclabs/deepcopy
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\DeepCopy::class => 1,
        // phar-io/manifest
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest::class => 1,
        // phar-io/version
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version::class => 1,
        // phpdocumentor/reflection-common
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Project::class => 1,
        // phpdocumentor/reflection-docblock
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock::class => 1,
        // phpdocumentor/type-resolver
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type::class => 1,
        // phpspec/prophecy
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophet::class => 1,
        // phpunit/phpunit
        \PHPUnit\Framework\TestCase::class => 2,
        // phpunit/php-code-coverage
        \SebastianBergmann\CodeCoverage\CodeCoverage::class => 1,
        // phpunit/php-file-iterator
        \SebastianBergmann\FileIterator\Facade::class => 1,
        // phpunit/php-invoker
        \SebastianBergmann\Invoker\Invoker::class => 1,
        // phpunit/php-text-template
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Text_Template::class => 1,
        // phpunit/php-timer
        \SebastianBergmann\Timer\Timer::class => 1,
        // phpunit/php-token-stream
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token::class => 1,
        // sebastian/code-unit-reverse-lookup
        \SebastianBergmann\CodeUnitReverseLookup\Wizard::class => 1,
        // sebastian/comparator
        \SebastianBergmann\Comparator\Comparator::class => 1,
        // sebastian/diff
        \SebastianBergmann\Diff\Diff::class => 1,
        // sebastian/environment
        \SebastianBergmann\Environment\Runtime::class => 1,
        // sebastian/exporter
        \SebastianBergmann\Exporter\Exporter::class => 1,
        // sebastian/global-state
        \SebastianBergmann\GlobalState\Snapshot::class => 1,
        // sebastian/object-enumerator
        \SebastianBergmann\ObjectEnumerator\Enumerator::class => 1,
        // sebastian/recursion-context
        \SebastianBergmann\RecursionContext\Context::class => 1,
        // sebastian/resource-operations
        \SebastianBergmann\ResourceOperations\ResourceOperations::class => 1,
        // sebastian/type
        \SebastianBergmann\Type\TypeName::class => 1,
        // sebastian/version
        \SebastianBergmann\Version::class => 1,
        // theseer/tokenizer
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Tokenizer::class => 1,
        // webmozart/assert
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert::class => 1,
    ];
    /**
     * @var string[]
     */
    private static $directories;
    /**
     * @throws Exception
     *
     * @return string[]
     */
    public function getBlacklistedDirectories() : array
    {
        $this->initialize();
        return self::$directories;
    }
    /**
     * @throws Exception
     */
    public function isBlacklisted(string $file) : bool
    {
        if (\defined('PHPUNIT_TESTSUITE')) {
            return \false;
        }
        $this->initialize();
        foreach (self::$directories as $directory) {
            if (\strpos($file, $directory) === 0) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @throws Exception
     */
    private function initialize() : void
    {
        if (self::$directories === null) {
            self::$directories = [];
            foreach (self::$blacklistedClassNames as $className => $parent) {
                if (!\class_exists($className)) {
                    continue;
                }
                try {
                    $directory = (new \ReflectionClass($className))->getFileName();
                    // @codeCoverageIgnoreStart
                } catch (\ReflectionException $e) {
                    throw new \PHPUnit\Util\Exception($e->getMessage(), (int) $e->getCode(), $e);
                }
                // @codeCoverageIgnoreEnd
                for ($i = 0; $i < $parent; $i++) {
                    $directory = \dirname($directory);
                }
                self::$directories[] = $directory;
            }
            // Hide process isolation workaround on Windows.
            if (\DIRECTORY_SEPARATOR === '\\') {
                // tempnam() prefix is limited to first 3 chars.
                // @see https://php.net/manual/en/function.tempnam.php
                self::$directories[] = \sys_get_temp_dir() . '\\PHP';
            }
        }
    }
}
