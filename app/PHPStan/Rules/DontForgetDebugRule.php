<?php
declare(strict_types=1);

namespace App\PHPStan\Rules;

use Illuminate\Support\Facades\Log;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use function count;

class DontForgetDebugRule implements Rule
{
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /** @var StaticCall $node due to @see getNodeType() */
        if ($this->isCalledOnLog($node, $scope) === false) {
            return [];
        }

        $methodName = $node->name->name;

        if ($methodName === 'channel') {
            return $this->channelMethodCallHandler($node, $scope);
        }

        if ($methodName === 'debug') {
            return [
                RuleErrorBuilder::message("Called Log::debug(). Don't forget to remove all debug calls.")
                    ->identifier('rules.dontForgetDebug')
                    ->line($node->getLine())
                    ->file($scope->getFile())
                    ->build(),
            ];
        }

        return [];
    }

    protected function channelMethodCallHandler(StaticCall $call, Scope $scope): array
    {
        if (count($call->args) === 0) {
            return [];
        }

        /** @var Arg $channelArg */
        $channelArg = $call->args[0];
        $channelArgValue = $channelArg->value;
        $channelArgValueType = $channelArgValue->getType();

        if ($channelArgValueType === 'Scalar_String') {
            /** @var String_ $channelArgValue */
            $value = $channelArgValue->value;
            if ($value !== 'debug') {
                return [];
            }
        }

        if ($channelArgValueType === 'Expr_ClassConstFetch') {
            /** @var ClassConstFetch $channelArgValue */
            $value = $channelArgValue->name->name;
            if ($value !== 'DEBUG') {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message("Called Log::channel('debug') which unacceptable.")
                ->identifier('rules.dontForgetDebug')
                ->line($call->getLine())
                ->file($scope->getFile())
                ->build(),
        ];
    }

    protected function isCalledOnLog(StaticCall $call, Scope $scope): bool
    {
        $class = $call->class;
        if ($class instanceof FullyQualified) {
            $type = new ObjectType($class->toString());
        } elseif ($class instanceof Expr) {
            $exprType = $scope->getType($class);

            if ($exprType instanceof ConstantStringType === false) {
                return false;
            }

            if ($exprType->isClassString() === false) {
                return false;
            }

            $type = new ObjectType($exprType->getValue());
        } else {
            // TODO can we handle relative names, do they even occur here?
            return false;
        }

        return (new ObjectType(Log::class))
            ->isSuperTypeOf($type)
            ->yes();
    }
}