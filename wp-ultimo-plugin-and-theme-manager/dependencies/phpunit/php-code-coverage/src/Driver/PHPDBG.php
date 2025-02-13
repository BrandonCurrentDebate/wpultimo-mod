<?php

declare (strict_types=1);
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Driver;

use SebastianBergmann\CodeCoverage\RuntimeException;
/**
 * Driver for PHPDBG's code coverage functionality.
 *
 * @codeCoverageIgnore
 */
final class PHPDBG implements \SebastianBergmann\CodeCoverage\Driver\Driver
{
    /**
     * @throws RuntimeException
     */
    public function __construct()
    {
        if (\PHP_SAPI !== 'phpdbg') {
            throw new \SebastianBergmann\CodeCoverage\RuntimeException('This driver requires the PHPDBG SAPI');
        }
        if (!\function_exists('WP_Ultimo_Plugin_And_Theme_Manager\\Dependencies\\phpdbg_start_oplog')) {
            throw new \SebastianBergmann\CodeCoverage\RuntimeException('This build of PHPDBG does not support code coverage');
        }
    }
    /**
     * Start collection of code coverage information.
     */
    public function start(bool $determineUnusedAndDead = \true) : void
    {
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpdbg_start_oplog();
    }
    /**
     * Stop collection of code coverage information.
     */
    public function stop() : array
    {
        static $fetchedLines = [];
        $dbgData = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpdbg_end_oplog();
        if ($fetchedLines == []) {
            $sourceLines = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpdbg_get_executable();
        } else {
            $newFiles = \array_diff(\get_included_files(), \array_keys($fetchedLines));
            $sourceLines = [];
            if ($newFiles) {
                $sourceLines = phpdbg_get_executable(['files' => $newFiles]);
            }
        }
        foreach ($sourceLines as $file => $lines) {
            foreach ($lines as $lineNo => $numExecuted) {
                $sourceLines[$file][$lineNo] = self::LINE_NOT_EXECUTED;
            }
        }
        $fetchedLines = \array_merge($fetchedLines, $sourceLines);
        return $this->detectExecutedLines($fetchedLines, $dbgData);
    }
    /**
     * Convert phpdbg based data into the format CodeCoverage expects
     */
    private function detectExecutedLines(array $sourceLines, array $dbgData) : array
    {
        foreach ($dbgData as $file => $coveredLines) {
            foreach ($coveredLines as $lineNo => $numExecuted) {
                // phpdbg also reports $lineNo=0 when e.g. exceptions get thrown.
                // make sure we only mark lines executed which are actually executable.
                if (isset($sourceLines[$file][$lineNo])) {
                    $sourceLines[$file][$lineNo] = self::LINE_EXECUTED;
                }
            }
        }
        return $sourceLines;
    }
}
