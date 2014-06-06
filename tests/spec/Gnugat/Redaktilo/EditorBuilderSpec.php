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

use PhpSpec\ObjectBehavior;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Replace\ReplaceEngine;
use Gnugat\Redaktilo\Replace\ReplaceStrategy;

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

        $editor->shouldHaveReplaceStrategies(array(
            'Gnugat\Redaktilo\Replace\LineReplaceStrategy',
        ));
    }

    function it_can_have_custom_strategies(SearchStrategy $searchStrategy, ReplaceStrategy $replaceStrategy)
    {
        $editor = $this
            ->addSearchStrategy($searchStrategy)
            ->addReplaceStrategy($replaceStrategy)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        $editor->shouldHaveSearchStrategiesCount(5);
        $editor->shouldHaveReplaceStrategiesCount(2);
    }

    function it_can_have_custom_engines(SearchEngine $searchEngine, ReplaceEngine $replaceEngine)
    {
        $editor = $this
            ->setSearchEngine($searchEngine)
            ->setReplaceEngine($replaceEngine)
            ->getEditor();

        $editor->shouldBeAnInstanceOf('Gnugat\Redaktilo\Editor');
        expect(static::readProperty($editor->getWrappedObject(), 'searchEngine'))->toBe($searchEngine);
        expect(static::readProperty($editor->getWrappedObject(), 'replaceEngine'))->toBe($replaceEngine);
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
            'haveReplaceStrategies' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'replaceEngine');
                $strategies = array_map('get_class', $readProperty($engine, 'replaceStrategies'));

                $constraint = new \PHPUnit_Framework_Constraint_IsEqual($expected);

                return $constraint->evaluate($strategies, '', true);
            },
            'haveSearchStrategiesCount' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'searchEngine');
                $strategies = $readProperty($engine, 'searchStrategies');

                return $expected == count($strategies);
            },
            'haveReplaceStrategiesCount' => function ($subject, $expected) use ($readProperty) {
                $engine = $readProperty($subject, 'replaceEngine');
                $strategies = $readProperty($engine, 'replaceStrategies');

                return $expected == count($strategies);
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
