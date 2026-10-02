<?php

namespace Ministan;

use PhpParser\Node;

interface Rule
{
    public function getNodeType(): string;

    public function processNode(Node $node, Scope $scope): array;
}
