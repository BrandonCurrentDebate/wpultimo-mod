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
namespace SebastianBergmann\CodeCoverage\Report\Html;

use SebastianBergmann\CodeCoverage\Node\AbstractNode;
use SebastianBergmann\CodeCoverage\Node\Directory as DirectoryNode;
use SebastianBergmann\CodeCoverage\Node\File as FileNode;
use SebastianBergmann\CodeCoverage\Version;
use SebastianBergmann\Environment\Runtime;
/**
 * Base class for node renderers.
 */
abstract class Renderer
{
    /**
     * @var string
     */
    protected $templatePath;
    /**
     * @var string
     */
    protected $generator;
    /**
     * @var string
     */
    protected $date;
    /**
     * @var int
     */
    protected $lowUpperBound;
    /**
     * @var int
     */
    protected $highLowerBound;
    /**
     * @var string
     */
    protected $version;
    public function __construct(string $templatePath, string $generator, string $date, int $lowUpperBound, int $highLowerBound)
    {
        $this->templatePath = $templatePath;
        $this->generator = $generator;
        $this->date = $date;
        $this->lowUpperBound = $lowUpperBound;
        $this->highLowerBound = $highLowerBound;
        $this->version = \SebastianBergmann\CodeCoverage\Version::id();
    }
    protected function renderItemTemplate(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Text_Template $template, array $data) : string
    {
        $numSeparator = '&nbsp;/&nbsp;';
        if (isset($data['numClasses']) && $data['numClasses'] > 0) {
            $classesLevel = $this->getColorLevel($data['testedClassesPercent']);
            $classesNumber = $data['numTestedClasses'] . $numSeparator . $data['numClasses'];
            $classesBar = $this->getCoverageBar($data['testedClassesPercent']);
        } else {
            $classesLevel = '';
            $classesNumber = '0' . $numSeparator . '0';
            $classesBar = '';
            $data['testedClassesPercentAsString'] = 'n/a';
        }
        if ($data['numMethods'] > 0) {
            $methodsLevel = $this->getColorLevel($data['testedMethodsPercent']);
            $methodsNumber = $data['numTestedMethods'] . $numSeparator . $data['numMethods'];
            $methodsBar = $this->getCoverageBar($data['testedMethodsPercent']);
        } else {
            $methodsLevel = '';
            $methodsNumber = '0' . $numSeparator . '0';
            $methodsBar = '';
            $data['testedMethodsPercentAsString'] = 'n/a';
        }
        if ($data['numExecutableLines'] > 0) {
            $linesLevel = $this->getColorLevel($data['linesExecutedPercent']);
            $linesNumber = $data['numExecutedLines'] . $numSeparator . $data['numExecutableLines'];
            $linesBar = $this->getCoverageBar($data['linesExecutedPercent']);
        } else {
            $linesLevel = '';
            $linesNumber = '0' . $numSeparator . '0';
            $linesBar = '';
            $data['linesExecutedPercentAsString'] = 'n/a';
        }
        $template->setVar(['icon' => $data['icon'] ?? '', 'crap' => $data['crap'] ?? '', 'name' => $data['name'], 'lines_bar' => $linesBar, 'lines_executed_percent' => $data['linesExecutedPercentAsString'], 'lines_level' => $linesLevel, 'lines_number' => $linesNumber, 'methods_bar' => $methodsBar, 'methods_tested_percent' => $data['testedMethodsPercentAsString'], 'methods_level' => $methodsLevel, 'methods_number' => $methodsNumber, 'classes_bar' => $classesBar, 'classes_tested_percent' => $data['testedClassesPercentAsString'] ?? '', 'classes_level' => $classesLevel, 'classes_number' => $classesNumber]);
        return $template->render();
    }
    protected function setCommonTemplateVariables(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Text_Template $template, \SebastianBergmann\CodeCoverage\Node\AbstractNode $node) : void
    {
        $template->setVar(['id' => $node->getId(), 'full_path' => $node->getPath(), 'path_to_root' => $this->getPathToRoot($node), 'breadcrumbs' => $this->getBreadcrumbs($node), 'date' => $this->date, 'version' => $this->version, 'runtime' => $this->getRuntimeString(), 'generator' => $this->generator, 'low_upper_bound' => $this->lowUpperBound, 'high_lower_bound' => $this->highLowerBound]);
    }
    protected function getBreadcrumbs(\SebastianBergmann\CodeCoverage\Node\AbstractNode $node) : string
    {
        $breadcrumbs = '';
        $path = $node->getPathAsArray();
        $pathToRoot = [];
        $max = \count($path);
        if ($node instanceof \SebastianBergmann\CodeCoverage\Node\File) {
            $max--;
        }
        for ($i = 0; $i < $max; $i++) {
            $pathToRoot[] = \str_repeat('../', $i);
        }
        foreach ($path as $step) {
            if ($step !== $node) {
                $breadcrumbs .= $this->getInactiveBreadcrumb($step, \array_pop($pathToRoot));
            } else {
                $breadcrumbs .= $this->getActiveBreadcrumb($step);
            }
        }
        return $breadcrumbs;
    }
    protected function getActiveBreadcrumb(\SebastianBergmann\CodeCoverage\Node\AbstractNode $node) : string
    {
        $buffer = \sprintf('         <li class="breadcrumb-item active">%s</li>' . "\n", $node->getName());
        if ($node instanceof \SebastianBergmann\CodeCoverage\Node\Directory) {
            $buffer .= '         <li class="breadcrumb-item">(<a href="dashboard.html">Dashboard</a>)</li>' . "\n";
        }
        return $buffer;
    }
    protected function getInactiveBreadcrumb(\SebastianBergmann\CodeCoverage\Node\AbstractNode $node, string $pathToRoot) : string
    {
        return \sprintf('         <li class="breadcrumb-item"><a href="%sindex.html">%s</a></li>' . "\n", $pathToRoot, $node->getName());
    }
    protected function getPathToRoot(\SebastianBergmann\CodeCoverage\Node\AbstractNode $node) : string
    {
        $id = $node->getId();
        $depth = \substr_count($id, '/');
        if ($id !== 'index' && $node instanceof \SebastianBergmann\CodeCoverage\Node\Directory) {
            $depth++;
        }
        return \str_repeat('../', $depth);
    }
    protected function getCoverageBar(float $percent) : string
    {
        $level = $this->getColorLevel($percent);
        $template = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Text_Template($this->templatePath . 'coverage_bar.html', '{{', '}}');
        $template->setVar(['level' => $level, 'percent' => \sprintf('%.2F', $percent)]);
        return $template->render();
    }
    protected function getColorLevel(float $percent) : string
    {
        if ($percent <= $this->lowUpperBound) {
            return 'danger';
        }
        if ($percent > $this->lowUpperBound && $percent < $this->highLowerBound) {
            return 'warning';
        }
        return 'success';
    }
    private function getRuntimeString() : string
    {
        $runtime = new \SebastianBergmann\Environment\Runtime();
        $buffer = \sprintf('<a href="%s" target="_top">%s %s</a>', $runtime->getVendorUrl(), $runtime->getName(), $runtime->getVersion());
        if ($runtime->hasXdebug() && !$runtime->hasPHPDBGCodeCoverage()) {
            $buffer .= \sprintf(' with <a href="https://xdebug.org/">Xdebug %s</a>', \phpversion('xdebug'));
        }
        if ($runtime->hasPCOV() && !$runtime->hasPHPDBGCodeCoverage()) {
            $buffer .= \sprintf(' with <a href="https://github.com/krakjoe/pcov">PCOV %s</a>', \phpversion('pcov'));
        }
        return $buffer;
    }
}
