<?php

declare (strict_types=1);
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer;

class Tokenizer
{
    /**
     * Token Map for "non-tokens"
     *
     * @var array
     */
    private $map = ['(' => 'T_OPEN_BRACKET', ')' => 'T_CLOSE_BRACKET', '[' => 'T_OPEN_SQUARE', ']' => 'T_CLOSE_SQUARE', '{' => 'T_OPEN_CURLY', '}' => 'T_CLOSE_CURLY', ';' => 'T_SEMICOLON', '.' => 'T_DOT', ',' => 'T_COMMA', '=' => 'T_EQUAL', '<' => 'T_LT', '>' => 'T_GT', '+' => 'T_PLUS', '-' => 'T_MINUS', '*' => 'T_MULT', '/' => 'T_DIV', '?' => 'T_QUESTION_MARK', '!' => 'T_EXCLAMATION_MARK', ':' => 'T_COLON', '"' => 'T_DOUBLE_QUOTES', '@' => 'T_AT', '&' => 'T_AMPERSAND', '%' => 'T_PERCENT', '|' => 'T_PIPE', '$' => 'T_DOLLAR', '^' => 'T_CARET', '~' => 'T_TILDE', '`' => 'T_BACKTICK'];
    public function parse(string $source) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollection
    {
        $result = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollection();
        if ($source === '') {
            return $result;
        }
        $tokens = \token_get_all($source);
        $lastToken = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token($tokens[0][2], 'Placeholder', '');
        foreach ($tokens as $pos => $tok) {
            if (\is_string($tok)) {
                $token = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token($lastToken->getLine(), $this->map[$tok], $tok);
                $result->addToken($token);
                $lastToken = $token;
                continue;
            }
            $line = $tok[2];
            $values = \preg_split('/\\R+/Uu', $tok[1]);
            foreach ($values as $v) {
                $token = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token($line, \token_name($tok[0]), $v);
                $lastToken = $token;
                $line++;
                if ($v === '') {
                    continue;
                }
                $result->addToken($token);
            }
        }
        return $this->fillBlanks($result, $lastToken->getLine());
    }
    private function fillBlanks(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollection $tokens, int $maxLine) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollection
    {
        $prev = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token(0, 'Placeholder', '');
        $final = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\TokenCollection();
        foreach ($tokens as $token) {
            if ($prev === null) {
                $final->addToken($token);
                $prev = $token;
                continue;
            }
            $gap = $token->getLine() - $prev->getLine();
            while ($gap > 1) {
                $linebreak = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token($prev->getLine() + 1, 'T_WHITESPACE', '');
                $final->addToken($linebreak);
                $prev = $linebreak;
                $gap--;
            }
            $final->addToken($token);
            $prev = $token;
        }
        $gap = $maxLine - $prev->getLine();
        while ($gap > 0) {
            $linebreak = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Token($prev->getLine() + 1, 'T_WHITESPACE', '');
            $final->addToken($linebreak);
            $prev = $linebreak;
            $gap--;
        }
        return $final;
    }
}
