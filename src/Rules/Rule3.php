<?php

namespace Ministan\Rules;

use Ministan\Rule;
use Ministan\Scope;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeFinder;

final class Rule3 implements Rule
{
    public function __construct(
        private array $ast,
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $className = $scope->getType($node->var);
        if ($className === null) {
            return [];
        }
        $methodName = $node->name->toString();

        $classes = (new NodeFinder())->findInstanceOf($this->ast, Node\Stmt\Class_::class);
        foreach ($classes as $class) {
            if ($class->name?->toString() !== $className) {
                continue;
            }
            if ($class->getMethod($methodName) !== null) {
                return [];
            }

            return ["Call to an undefined method {$className}::{$methodName}()."];
        }

        return [];
    }
}
