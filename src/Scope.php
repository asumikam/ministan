<?php

namespace Ministan;

use PhpParser\Node\Expr;

final class Scope
{
    public function __construct(
        private array $variableTypes = [],
    ) {
    }

    public function enterClass(string $className): self
    {
        return new self(['this' => $className]);
    }

    public function getType(Expr $expr): string|null
    {
        if ($expr instanceof Expr\Variable) {
            return $this->variableTypes[$expr->name] ?? null;
        }

        return null;
    }
}
