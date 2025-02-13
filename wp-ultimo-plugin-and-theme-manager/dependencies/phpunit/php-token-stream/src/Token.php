<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies;

/*
 * This file is part of php-token-stream.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * A PHP token.
 */
abstract class PHP_Token
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var int
     */
    protected $line;
    /**
     * @var PHP_Token_Stream
     */
    protected $tokenStream;
    /**
     * @var int
     */
    protected $id;
    /**
     * @param string           $text
     * @param int              $line
     * @param PHP_Token_Stream $tokenStream
     * @param int              $id
     */
    public function __construct($text, $line, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Stream $tokenStream, $id)
    {
        $this->text = $text;
        $this->line = $line;
        $this->tokenStream = $tokenStream;
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }
    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
abstract class PHP_TokenWithScope extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
    /**
     * @var int
     */
    protected $endTokenId;
    /**
     * Get the docblock for this token
     *
     * This method will fetch the docblock belonging to the current token. The
     * docblock must be placed on the line directly above the token to be
     * recognized.
     *
     * @return string|null Returns the docblock as a string if found
     */
    public function getDocblock()
    {
        $tokens = $this->tokenStream->tokens();
        $currentLineNumber = $tokens[$this->id]->getLine();
        $prevLineNumber = $currentLineNumber - 1;
        for ($i = $this->id - 1; $i; $i--) {
            if (!isset($tokens[$i])) {
                return;
            }
            if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_FUNCTION || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_CLASS || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_TRAIT) {
                // Some other trait, class or function, no docblock can be
                // used for the current token
                break;
            }
            $line = $tokens[$i]->getLine();
            if ($line == $currentLineNumber || $line == $prevLineNumber && $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_WHITESPACE) {
                continue;
            }
            if ($line < $currentLineNumber && !$tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_DOC_COMMENT) {
                break;
            }
            return (string) $tokens[$i];
        }
    }
    /**
     * @return int
     */
    public function getEndTokenId()
    {
        $block = 0;
        $i = $this->id;
        $tokens = $this->tokenStream->tokens();
        while ($this->endTokenId === null && isset($tokens[$i])) {
            if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_CURLY || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_DOLLAR_OPEN_CURLY_BRACES || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_CURLY_OPEN) {
                $block++;
            } elseif ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_CLOSE_CURLY) {
                $block--;
                if ($block === 0) {
                    $this->endTokenId = $i;
                }
            } elseif (($this instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_FUNCTION || $this instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_NAMESPACE) && $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_SEMICOLON) {
                if ($block === 0) {
                    $this->endTokenId = $i;
                }
            }
            $i++;
        }
        if ($this->endTokenId === null) {
            $this->endTokenId = $this->id;
        }
        return $this->endTokenId;
    }
    /**
     * @return int
     */
    public function getEndLine()
    {
        return $this->tokenStream[$this->getEndTokenId()]->getLine();
    }
}
abstract class PHP_TokenWithScopeAndVisibility extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_TokenWithScope
{
    /**
     * @return string
     */
    public function getVisibility()
    {
        $tokens = $this->tokenStream->tokens();
        for ($i = $this->id - 2; $i > $this->id - 7; $i -= 2) {
            if (isset($tokens[$i]) && ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PRIVATE || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PROTECTED || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PUBLIC)) {
                return \strtolower(\str_replace('PHP_Token_', '', \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Util::getClass($tokens[$i])));
            }
            if (isset($tokens[$i]) && !($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_STATIC || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_FINAL || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_ABSTRACT)) {
                // no keywords; stop visibility search
                break;
            }
        }
    }
    /**
     * @return string
     */
    public function getKeywords()
    {
        $keywords = [];
        $tokens = $this->tokenStream->tokens();
        for ($i = $this->id - 2; $i > $this->id - 7; $i -= 2) {
            if (isset($tokens[$i]) && ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PRIVATE || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PROTECTED || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_PUBLIC)) {
                continue;
            }
            if (isset($tokens[$i]) && ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_STATIC || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_FINAL || $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_ABSTRACT)) {
                $keywords[] = \strtolower(\str_replace('PHP_Token_', '', \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Util::getClass($tokens[$i])));
            }
        }
        return \implode(',', $keywords);
    }
}
abstract class PHP_Token_Includes extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $type;
    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name === null) {
            $this->process();
        }
        return $this->name;
    }
    /**
     * @return string
     */
    public function getType()
    {
        if ($this->type === null) {
            $this->process();
        }
        return $this->type;
    }
    private function process()
    {
        $tokens = $this->tokenStream->tokens();
        if ($tokens[$this->id + 2] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_CONSTANT_ENCAPSED_STRING) {
            $this->name = \trim($tokens[$this->id + 2], "'\"");
            $this->type = \strtolower(\str_replace('PHP_Token_', '', \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Util::getClass($tokens[$this->id])));
        }
    }
}
class PHP_Token_FUNCTION extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_TokenWithScopeAndVisibility
{
    /**
     * @var array
     */
    protected $arguments;
    /**
     * @var int
     */
    protected $ccn;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $signature;
    /**
     * @var bool
     */
    private $anonymous = \false;
    /**
     * @return array
     */
    public function getArguments()
    {
        if ($this->arguments !== null) {
            return $this->arguments;
        }
        $this->arguments = [];
        $tokens = $this->tokenStream->tokens();
        $typeDeclaration = null;
        // Search for first token inside brackets
        $i = $this->id + 2;
        while (!$tokens[$i - 1] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_BRACKET) {
            $i++;
        }
        while (!$tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_CLOSE_BRACKET) {
            if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_STRING) {
                $typeDeclaration = (string) $tokens[$i];
            } elseif ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_VARIABLE) {
                $this->arguments[(string) $tokens[$i]] = $typeDeclaration;
                $typeDeclaration = null;
            }
            $i++;
        }
        return $this->arguments;
    }
    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name !== null) {
            return $this->name;
        }
        $tokens = $this->tokenStream->tokens();
        $i = $this->id + 1;
        if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_WHITESPACE) {
            $i++;
        }
        if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_AMPERSAND) {
            $i++;
        }
        if ($tokens[$i + 1] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_BRACKET) {
            $this->name = (string) $tokens[$i];
        } elseif ($tokens[$i + 1] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_WHITESPACE && $tokens[$i + 2] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_BRACKET) {
            $this->name = (string) $tokens[$i];
        } else {
            $this->anonymous = \true;
            $this->name = \sprintf('anonymousFunction:%s#%s', $this->getLine(), $this->getId());
        }
        if (!$this->isAnonymous()) {
            for ($i = $this->id; $i; --$i) {
                if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_NAMESPACE) {
                    $this->name = $tokens[$i]->getName() . '\\' . $this->name;
                    break;
                }
                if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_INTERFACE) {
                    break;
                }
            }
        }
        return $this->name;
    }
    /**
     * @return int
     */
    public function getCCN()
    {
        if ($this->ccn !== null) {
            return $this->ccn;
        }
        $this->ccn = 1;
        $end = $this->getEndTokenId();
        $tokens = $this->tokenStream->tokens();
        for ($i = $this->id; $i <= $end; $i++) {
            switch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Util::getClass($tokens[$i])) {
                case 'PHP_Token_IF':
                case 'PHP_Token_ELSEIF':
                case 'PHP_Token_FOR':
                case 'PHP_Token_FOREACH':
                case 'PHP_Token_WHILE':
                case 'PHP_Token_CASE':
                case 'PHP_Token_CATCH':
                case 'PHP_Token_BOOLEAN_AND':
                case 'PHP_Token_LOGICAL_AND':
                case 'PHP_Token_BOOLEAN_OR':
                case 'PHP_Token_LOGICAL_OR':
                case 'PHP_Token_QUESTION_MARK':
                    $this->ccn++;
                    break;
            }
        }
        return $this->ccn;
    }
    /**
     * @return string
     */
    public function getSignature()
    {
        if ($this->signature !== null) {
            return $this->signature;
        }
        if ($this->isAnonymous()) {
            $this->signature = 'anonymousFunction';
            $i = $this->id + 1;
        } else {
            $this->signature = '';
            $i = $this->id + 2;
        }
        $tokens = $this->tokenStream->tokens();
        while (isset($tokens[$i]) && !$tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_CURLY && !$tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_SEMICOLON) {
            $this->signature .= $tokens[$i++];
        }
        $this->signature = \trim($this->signature);
        return $this->signature;
    }
    /**
     * @return bool
     */
    public function isAnonymous()
    {
        return $this->anonymous;
    }
}
class PHP_Token_INTERFACE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_TokenWithScopeAndVisibility
{
    /**
     * @var array
     */
    protected $interfaces;
    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->tokenStream[$this->id + 2];
    }
    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->tokenStream[$this->id + 4] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_EXTENDS;
    }
    /**
     * @return array
     */
    public function getPackage()
    {
        $className = $this->getName();
        $docComment = $this->getDocblock();
        $result = ['namespace' => '', 'fullPackage' => '', 'category' => '', 'package' => '', 'subpackage' => ''];
        for ($i = $this->id; $i; --$i) {
            if ($this->tokenStream[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_NAMESPACE) {
                $result['namespace'] = $this->tokenStream[$i]->getName();
                break;
            }
        }
        if (\preg_match('/@category[\\s]+([\\.\\w]+)/', $docComment, $matches)) {
            $result['category'] = $matches[1];
        }
        if (\preg_match('/@package[\\s]+([\\.\\w]+)/', $docComment, $matches)) {
            $result['package'] = $matches[1];
            $result['fullPackage'] = $matches[1];
        }
        if (\preg_match('/@subpackage[\\s]+([\\.\\w]+)/', $docComment, $matches)) {
            $result['subpackage'] = $matches[1];
            $result['fullPackage'] .= '.' . $matches[1];
        }
        if (empty($result['fullPackage'])) {
            $result['fullPackage'] = $this->arrayToName(\explode('_', \str_replace('\\', '_', $className)), '.');
        }
        return $result;
    }
    /**
     * @param array  $parts
     * @param string $join
     *
     * @return string
     */
    protected function arrayToName(array $parts, $join = '\\')
    {
        $result = '';
        if (\count($parts) > 1) {
            \array_pop($parts);
            $result = \implode($join, $parts);
        }
        return $result;
    }
    /**
     * @return bool|string
     */
    public function getParent()
    {
        if (!$this->hasParent()) {
            return \false;
        }
        $i = $this->id + 6;
        $tokens = $this->tokenStream->tokens();
        $className = (string) $tokens[$i];
        while (isset($tokens[$i + 1]) && !$tokens[$i + 1] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_WHITESPACE) {
            $className .= (string) $tokens[++$i];
        }
        return $className;
    }
    /**
     * @return bool
     */
    public function hasInterfaces()
    {
        return isset($this->tokenStream[$this->id + 4]) && $this->tokenStream[$this->id + 4] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_IMPLEMENTS || isset($this->tokenStream[$this->id + 8]) && $this->tokenStream[$this->id + 8] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_IMPLEMENTS;
    }
    /**
     * @return array|bool
     */
    public function getInterfaces()
    {
        if ($this->interfaces !== null) {
            return $this->interfaces;
        }
        if (!$this->hasInterfaces()) {
            return $this->interfaces = \false;
        }
        if ($this->tokenStream[$this->id + 4] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_IMPLEMENTS) {
            $i = $this->id + 3;
        } else {
            $i = $this->id + 7;
        }
        $tokens = $this->tokenStream->tokens();
        while (!$tokens[$i + 1] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_CURLY) {
            $i++;
            if ($tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_STRING) {
                $this->interfaces[] = (string) $tokens[$i];
            }
        }
        return $this->interfaces;
    }
}
class PHP_Token_ABSTRACT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_AMPERSAND extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_AND_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ARRAY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ARRAY_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_AS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_AT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BACKTICK extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BAD_CHARACTER extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BOOLEAN_AND extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BOOLEAN_OR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BOOL_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_BREAK extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CARET extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CASE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CATCH extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CHARACTER extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLASS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_INTERFACE
{
    /**
     * @var bool
     */
    private $anonymous = \false;
    /**
     * @var string
     */
    private $name;
    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name !== null) {
            return $this->name;
        }
        $next = $this->tokenStream[$this->id + 1];
        if ($next instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_WHITESPACE) {
            $next = $this->tokenStream[$this->id + 2];
        }
        if ($next instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_STRING) {
            $this->name = (string) $next;
            return $this->name;
        }
        if ($next instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_OPEN_CURLY || $next instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_EXTENDS || $next instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_IMPLEMENTS) {
            $this->name = \sprintf('AnonymousClass:%s#%s', $this->getLine(), $this->getId());
            $this->anonymous = \true;
            return $this->name;
        }
    }
    public function isAnonymous()
    {
        return $this->anonymous;
    }
}
class PHP_Token_CLASS_C extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLASS_NAME_CONSTANT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLONE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLOSE_BRACKET extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLOSE_CURLY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLOSE_SQUARE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CLOSE_TAG extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_COLON extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_COMMA extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_COMMENT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CONCAT_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CONST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CONSTANT_ENCAPSED_STRING extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CONTINUE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_CURLY_OPEN extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DEC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DECLARE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DEFAULT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DIV extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DIV_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DNUMBER extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DO extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOC_COMMENT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOLLAR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOLLAR_OPEN_CURLY_BRACES extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOUBLE_ARROW extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOUBLE_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOUBLE_COLON extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_DOUBLE_QUOTES extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ECHO extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ELSE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ELSEIF extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EMPTY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENCAPSED_AND_WHITESPACE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDDECLARE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDFOR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDFOREACH extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDIF extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDSWITCH extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ENDWHILE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_END_HEREDOC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EVAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EXCLAMATION_MARK extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EXIT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_EXTENDS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FILE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FINAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FOR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FOREACH extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FUNC_C extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_GLOBAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_GT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IF extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IMPLEMENTS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_INC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_INCLUDE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Includes
{
}
class PHP_Token_INCLUDE_ONCE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Includes
{
}
class PHP_Token_INLINE_HTML extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_INSTANCEOF extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_INT_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_ISSET extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_GREATER_OR_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_IDENTICAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_NOT_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_NOT_IDENTICAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_IS_SMALLER_OR_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LINE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LIST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LNUMBER extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LOGICAL_AND extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LOGICAL_OR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LOGICAL_XOR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_LT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_METHOD_C extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_MINUS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_MINUS_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_MOD_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_MULT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_MUL_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_NEW extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_NUM_STRING extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OBJECT_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OBJECT_OPERATOR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OPEN_BRACKET extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OPEN_CURLY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OPEN_SQUARE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OPEN_TAG extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OPEN_TAG_WITH_ECHO extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_OR_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PAAMAYIM_NEKUDOTAYIM extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PERCENT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PIPE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PLUS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PLUS_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PRINT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PRIVATE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PROTECTED extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_PUBLIC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_QUESTION_MARK extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_REQUIRE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Includes
{
}
class PHP_Token_REQUIRE_ONCE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_Includes
{
}
class PHP_Token_RETURN extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SEMICOLON extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SL_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SR_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_START_HEREDOC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_STATIC extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_STRING extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_STRING_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_STRING_VARNAME extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SWITCH extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_THROW extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_TILDE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_TRY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_UNSET extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_UNSET_CAST extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_USE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_USE_FUNCTION extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_VAR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_VARIABLE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_WHILE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_WHITESPACE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_XOR_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 5.1
class PHP_Token_HALT_COMPILER extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 5.3
class PHP_Token_DIR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_GOTO extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_NAMESPACE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_TokenWithScope
{
    /**
     * @return string
     */
    public function getName()
    {
        $tokens = $this->tokenStream->tokens();
        $namespace = (string) $tokens[$this->id + 2];
        for ($i = $this->id + 3;; $i += 2) {
            if (isset($tokens[$i]) && $tokens[$i] instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_NS_SEPARATOR) {
                $namespace .= '\\' . $tokens[$i + 1];
            } else {
                break;
            }
        }
        return $namespace;
    }
}
class PHP_Token_NS_C extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_NS_SEPARATOR extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 5.4
class PHP_Token_CALLABLE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_INSTEADOF extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_TRAIT extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token_INTERFACE
{
}
class PHP_Token_TRAIT_C extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 5.5
class PHP_Token_FINALLY extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_YIELD extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 5.6
class PHP_Token_ELLIPSIS extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_POW extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_POW_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 7.0
class PHP_Token_COALESCE extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_SPACESHIP extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_YIELD_FROM extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
// Tokens introduced in PHP 7.4
class PHP_Token_COALESCE_EQUAL extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
class PHP_Token_FN extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHP_Token
{
}
