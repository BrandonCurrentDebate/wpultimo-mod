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
namespace PHPUnit\Framework\MockObject\Rule;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Invocation as BaseInvocation;
use PHPUnit\Framework\MockObject\Verifiable;
use PHPUnit\Framework\SelfDescribing;
interface ParametersRule extends \PHPUnit\Framework\SelfDescribing, \PHPUnit\Framework\MockObject\Verifiable
{
    /**
     * @throws ExpectationFailedException if the invocation violates the rule
     */
    public function apply(\PHPUnit\Framework\MockObject\Invocation $invocation) : void;
    public function verify() : void;
}
