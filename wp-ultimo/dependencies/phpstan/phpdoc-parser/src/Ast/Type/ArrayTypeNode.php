<?php

declare (strict_types=1);
namespace WP_Ultimo\Dependencies\PHPStan\PhpDocParser\Ast\Type;

use WP_Ultimo\Dependencies\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ArrayTypeNode implements TypeNode
{
    use NodeAttributes;
    /** @var TypeNode */
    public $type;
    public function __construct(TypeNode $type)
    {
        $this->type = $type;
    }
    public function __toString() : string
    {
        if ($this->type instanceof CallableTypeNode || $this->type instanceof ConstTypeNode || $this->type instanceof NullableTypeNode) {
            return '(' . $this->type . ')[]';
        }
        return $this->type . '[]';
    }
}
