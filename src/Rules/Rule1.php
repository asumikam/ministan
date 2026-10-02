<?php

namespace Ministan\Rules;

use Ministan\Rule;
use Ministan\Scope;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeFinder;

final class Rule1 implements Rule
{
    public function __construct(
        private array $ast,
    ) {
    }

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $name = $node->name->toString();

        $functions = (new NodeFinder())->findInstanceOf($this->ast, Node\Stmt\Function_::class);
        foreach ($functions as $function) {
            if ($function->name->toLowerString() === strtolower($name)) {
                return [];
            }
        }

        return ["Function {$name} not found."];
    }
}
