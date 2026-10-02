<?php

require __DIR__ . '/../vendor/autoload.php';

use Ministan\FileAnalyserCallback;
use Ministan\NodeScopeResolver;
use Ministan\Rule;
use Ministan\Rules\Rule1;
use Ministan\Rules\Rule2;
use Ministan\Rules\Rule3;
use Ministan\Scope;
use PhpParser\ParserFactory;

$file = $argv[1];

$ast = (new ParserFactory())->createForHostVersion()->parse(file_get_contents($file));

$rules = [
    new Rule1($ast),
    new Rule2($ast),
    new Rule3($ast),
];

$nodeCallback = new FileAnalyserCallback($rules);

(new NodeScopeResolver())->processNodes($ast, new Scope(), $nodeCallback);

$errors = $nodeCallback->getErrors();
echo $errors === [] ? "[OK] No errors\n" : implode("\n", $errors) . "\n";
exit($errors === [] ? 0 : 1);
