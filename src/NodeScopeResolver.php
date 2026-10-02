<?php

namespace Ministan;

use PhpParser\Node;

final class NodeScopeResolver
{
    public function processNodes(array $nodes, Scope $scope, callable $nodeCallback): Scope
    {
        foreach ($nodes as $node) {
            $scope = $this->processNode($node, $scope, $nodeCallback);
        }

        return $scope;
    }

    private function processNode(Node $node, Scope $scope, callable $nodeCallback): Scope
    {
        $nodeCallback($node, $scope);

        if ($node instanceof Node\Stmt\Class_ && $node->name !== null) {
            $this->processSubNodes($node, $scope->enterClass($node->name->toString()), $nodeCallback);

            return $scope;
        }

        if ($node instanceof Node\Expr\Assign
            && $node->var instanceof Node\Expr\Variable
            && is_string($node->var->name)
        ) {
            $scope = $this->processNode($node->expr, $scope, $nodeCallback);

            return $scope->assignVariable($node->var->name, $scope->getType($node->expr));
        }

        return $this->processSubNodes($node, $scope, $nodeCallback);
    }

    private function processSubNodes(Node $node, Scope $scope, callable $nodeCallback): Scope
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode = $node->$name;
            if ($subNode instanceof Node) {
                $scope = $this->processNode($subNode, $scope, $nodeCallback);
            } elseif (is_array($subNode)) {
                $scope = $this->processNodes(
                    array_filter($subNode, fn ($n) => $n instanceof Node),
                    $scope,
                    $nodeCallback,
                );
            }
        }

        return $scope;
    }
}
