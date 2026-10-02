<?php

namespace Ministan;

use PhpParser\Node;

final class NodeScopeResolver
{
    public function processNodes(array $nodes, Scope $scope, callable $nodeCallback): void
    {
        foreach ($nodes as $node) {
            $this->processNode($node, $scope, $nodeCallback);
        }
    }

    private function processNode(Node $node, Scope $scope, callable $nodeCallback): void
    {
        $nodeCallback($node, $scope);

        if ($node instanceof Node\Stmt\Class_ && $node->name !== null) {
            $scope = $scope->enterClass($node->name->toString());
        }

        foreach ($node->getSubNodeNames() as $name) {
            $subNode = $node->$name;
            if ($subNode instanceof Node) {
                $this->processNode($subNode, $scope, $nodeCallback);
            } elseif (is_array($subNode)) {
                $this->processNodes(
                    array_filter($subNode, fn ($n) => $n instanceof Node),
                    $scope,
                    $nodeCallback,
                );
            }
        }
    }
}
