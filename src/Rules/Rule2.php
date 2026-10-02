<?php

namespace Ministan\Rules;

use Ministan\Rule;
use Ministan\Scope;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeFinder;

final class Rule2 implements Rule
{
    public function __construct(
        private array $ast,
    ) {
    }

    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $className = $node->class->toString();
        $methodName = $node->name->toString();

        $classes = (new NodeFinder())->findInstanceOf($this->ast, Node\Stmt\Class_::class);
        foreach ($classes as $class) {
            if ($class->name?->toString() !== $className) {
                continue;
            }
            if ($class->getMethod($methodName) !== null) {
                return [];
            }

            return ["Call to an undefined static method {$className}::{$methodName}()."];
        }

        return [];
    }
}
