<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Command\Command;
use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use PhpSpec\ObjectBehavior;

class EditorBuilderSpec extends ObjectBehavior
{
    function it_can_build_the_default_editor()
    {
        $editor = $this->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');

        $editor->shouldHaveSearchStrategies(array(
            'Gnugat\Redaktilo\Search\PhpSearchStrategy',
            'Gnugat\Redaktilo\Search\LineNumberSearchStrategy',
            'Gnugat\Redaktilo\Search\LineRegexSearchStrategy',
            'Gnugat\Redaktilo\Search\SameSearchStrategy',
        ));

        $editor->shouldHaveCommands(array(
            'insert_above' => 'Gnugat\Redaktilo\Command\LineInsertAboveCommand',
            'insert_below' => 'Gnugat\Redaktilo\Command\LineInsertBelowCommand',
            'jump_above' => 'Gnugat\Redaktilo\Command\LineJumpAboveCommand',
            'jump_below' => 'Gnugat\Redaktilo\Command\LineJumpBelowCommand',
            'replace' => 'Gnugat\Redaktilo\Command\LineReplaceCommand',
            'remove' => 'Gnugat\Redaktilo\Command\LineRemoveCommand',
        ));
    }

    function it_can_have_custom_search_strategies(SearchStrategy $searchStrategy)
    {
        $editor = $this
            ->addSearchStrategy($searchStrategy)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        $editor->shouldHaveSearchStrategiesCount(5);
    }

    function it_can_have_a_custom_search_engine(SearchEngine $searchEngine)
    {
        $editor = $this
            ->setSearchEngine($searchEngine)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        expect(static::readProperty($editor->getWrappedObject(), 'searchEngine'))->toBe($searchEngine);
    }

    function it_can_have_custom_commands(Command $command)
    {
        $editor = $this
            ->addCommand($command)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        $editor->shouldHaveCommandCount(7);
    }

    function it_can_have_a_custom_command_invoker(CommandInvoker $commandInvoker)
    {
        $editor = $this
            ->setCommandInvoker($commandInvoker)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        expect(static::readProperty($editor->getWrappedObject(), 'commandInvoker'))->toBe($commandInvoker);
    }

    function getMatchers()
    {
        $readProperty = function ($object, $propertyName) {
            return EditorBuilderSpec::readProperty($object, $propertyName);
        };

        return array(
            'haveSearchStrategies' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'searchEngine');
                $strategies = array_map(
                    'get_class',
                    array_filter(
                        call_user_func_array(
                            'array_merge',
                            $readProperty($engine, 'searchStrategies')
                        )
                    )
                );

                $constraint = new \PHPUnit_Framework_Constraint_IsEqual($expected);

                return $constraint->evaluate($strategies, '', true);
            },
            'haveCommands' => function ($subject, $expected) use ($readProperty) {
                $commandInvoker = $readProperty($subject, 'commandInvoker');
                $commands = array_map('get_class', $readProperty($commandInvoker, 'commands'));

                $constraint = new \PHPUnit_Framework_Constraint_IsEqual($expected);

                return $constraint->evaluate($commands, '', true);
            },
            'haveSearchStrategiesCount' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'searchEngine');
                $count = array_reduce(
                    $readProperty($engine, 'searchStrategies'),
                    function ($carry, $item) {
                        return $carry + count($item);
                    }
                );

                return $expected == $count;
            },
            'haveCommandCount' => function ($subject, $expected) use ($readProperty) {
                $commandInvoker = $readProperty($subject, 'commandInvoker');
                $commands = $readProperty($commandInvoker, 'commands');

                return $expected == count($commands);
            }
        );
    }

    static function readProperty($object, $propertyName)
    {
        $reflectedClass = new \ReflectionObject($object);
        $property = $reflectedClass->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
