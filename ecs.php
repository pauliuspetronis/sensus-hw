<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->cacheDirectory('.ecs_cache');
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->sets([SetList::PSR_12]);

    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => [
            'author',
            'authors',
            'package',
            'group',
        ],
    ]);

    $ecsConfig->ruleWithConfiguration(
        ArraySyntaxFixer::class,
        [
            'syntax' => 'short',
        ]
    );

    $ecsConfig->ruleWithConfiguration(
        ArraySyntaxFixer::class,
        ['syntax' => 'short'],
    );

    $ecsConfig->ruleWithConfiguration(
        \PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer::class,
        ['elements' => ['arrays', 'arguments']],
    );

    $ecsConfig->ruleWithConfiguration(
        \PhpCsFixer\Fixer\CastNotation\CastSpacesFixer::class,
        ['space' => 'none'],
    );
    $ecsConfig->ruleWithConfiguration(
        \PhpCsFixer\Fixer\Operator\ConcatSpaceFixer::class,
        ['spacing' => 'one'],
    );
    $ecsConfig->ruleWithConfiguration(
        \PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer::class,
        ['single_line' => false],
    );

    $ecsConfig->rule(\PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Import\NoUnusedImportsFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer::class);
};
