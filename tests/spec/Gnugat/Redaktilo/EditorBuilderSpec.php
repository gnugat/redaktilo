<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\Command\Command;
use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Filesystem;
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
            'Gnugat\Redaktilo\Search\LineRegexSearchStrategy',
            'Gnugat\Redaktilo\Search\SubstringSearchStrategy',
            'Gnugat\Redaktilo\Search\LineNumberSearchStrategy',
        ));

        $editor->shouldHaveCommands(array(
            'insert' => 'Gnugat\Redaktilo\Command\LineInsertCommand',
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
        $editor->shouldHaveCommandCount(4);
    }

    function it_can_have_a_custom_command_invoker(CommandInvoker $commandInvoker)
    {
        $editor = $this
            ->setCommandInvoker($commandInvoker)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        expect(static::readProperty($editor->getWrappedObject(), 'commandInvoker'))->toBe($commandInvoker);
    }

    function it_can_have_a_custom_filesystem(Filesystem $filesystem)
    {
        $editor = $this
            ->setFilesystem($filesystem)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        expect(static::readProperty($editor->getWrappedObject(), 'filesystem'))->toBe($filesystem);
    }

    function getMatchers()
    {
        $readProperty = function ($object, $propertyName) {
            return EditorBuilderSpec::readProperty($object, $propertyName);
        };

        return array(
            'haveSearchStrategies' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'searchEngine');
                $strategies = array_map('get_class', $readProperty($engine, 'searchStrategies'));

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
                $strategies = $readProperty($engine, 'searchStrategies');

                return $expected == count($strategies);
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
