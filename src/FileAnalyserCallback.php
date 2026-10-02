<?php

namespace Ministan;

use PhpParser\Node;

final class FileAnalyserCallback
{
    private array $errors = [];

    public function __construct(
        private array $rules,
    ) {
    }

    public function __invoke(Node $node, Scope $scope): void
    {
        foreach ($this->rules as $rule) {
            if ($node instanceof ($rule->getNodeType())) {
                foreach ($rule->processNode($node, $scope) as $message) {
                    $this->errors[] = sprintf('%d: %s', $node->getStartLine(), $message);
                }
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
