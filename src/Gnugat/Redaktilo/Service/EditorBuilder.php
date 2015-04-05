<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Command\Command;
use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Command\LineInsertAboveCommand;
use Gnugat\Redaktilo\Command\LineInsertBelowCommand;
use Gnugat\Redaktilo\Command\LineRemoveCommand;
use Gnugat\Redaktilo\Command\LineReplaceAllCommand;
use Gnugat\Redaktilo\Command\LineReplaceCommand;
use Gnugat\Redaktilo\Command\Sanitizer\LocationSanitizer;
use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Search\LineNumberSearchStrategy;
use Gnugat\Redaktilo\Search\LineRegexSearchStrategy;
use Gnugat\Redaktilo\Search\PhpSearchStrategy;
use Gnugat\Redaktilo\Search\SameSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class EditorBuilder
{
    /** @var TextToPhpConverter */
    private $phpConverter;

    /** @var SearchEngine|null */
    private $searchEngine;

    /** @var SearchStrategy[][] */
    private $searchStrategies = array();

    /** @var CommandInvoker|null */
    private $commandInvoker;

    /** @var Command[] */
    private $commands = array();

    /** @var Filesystem */
    private $filesystem;

    /** @var TextSanitizer */
    private $textSanitizer;

    /** @var LocationSanitizer */
    private $locationSanitizer;

    /** @var ContentFactory */
    private $contentFactory;

    /** @return TextToPhpConverter */
    protected function getPhpConverter()
    {
        if ($this->phpConverter) {
            return $this->phpConverter;
        }
        $tokenBuilder = new TokenBuilder();

        return $this->phpConverter = new TextToPhpConverter($tokenBuilder);
    }

    /** @return SearchEngine */
    protected function getSearchEngine()
    {
        if ($this->searchEngine) {
            return $this->searchEngine;
        }

        $engine = new SearchEngine();
        $phpConverter = $this->getPhpConverter();

        $engine->registerStrategy(new LineRegexSearchStrategy(), 20);
        $engine->registerStrategy(new SameSearchStrategy(), 10);
        $engine->registerStrategy(new LineNumberSearchStrategy());
        $engine->registerStrategy(new PhpSearchStrategy($phpConverter));

        foreach ($this->searchStrategies as $priority => $strategies) {
            foreach ($strategies as $strategy) {
                $engine->registerStrategy($strategy, $priority);
            }
        }

        return $engine;
    }

    /** @return CommandInvoker */
    protected function getCommandInvoker()
    {
        if ($this->commandInvoker) {
            return $this->commandInvoker;
        }
        $commandInvoker = new CommandInvoker();

        $commandInvoker->addCommand(new LineInsertAboveCommand($this->getTextSanitizer(), $this->getLocationSanitizer()));
        $commandInvoker->addCommand(new LineInsertBelowCommand($this->getTextSanitizer(), $this->getLocationSanitizer()));
        $commandInvoker->addCommand(new LineReplaceAllCommand($this->getContentFactory(), $this->getTextSanitizer()));
        $commandInvoker->addCommand(new LineReplaceCommand($this->getTextSanitizer(), $this->getLocationSanitizer()));
        $commandInvoker->addCommand(new LineRemoveCommand($this->getTextSanitizer(), $this->getLocationSanitizer()));

        foreach ($this->commands as $command) {
            $commandInvoker->addCommand($command);
        }

        return $commandInvoker;
    }

    /** @return Filesystem */
    protected function getFilesystem()
    {
        if ($this->filesystem) {
            return $this->filesystem;
        }

        return new Filesystem(new SymfonyFilesystem(), $this->getContentFactory());
    }

    /** @return Editor */
    public function getEditor()
    {
        return new Editor(
            $this->getFilesystem(),
            $this->getSearchEngine(),
            $this->getCommandInvoker()
        );
    }

    /**
     * @param SearchStrategy $searchStrategy
     * @param int            $priority
     *
     * @return $this
     */
    public function addSearchStrategy(SearchStrategy $searchStrategy, $priority = 0)
    {
        $this->searchStrategies[$priority][] = $searchStrategy;

        return $this;
    }

    /**
     * @param SearchEngine $searchEngine
     *
     * @return $this
     */
    public function setSearchEngine(SearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;

        return $this;
    }

    /**
     * @param Command $command
     *
     * @return $this
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @param CommandInvoker $commandInvoker
     *
     * @return $this
     */
    public function setCommandInvoker(CommandInvoker $commandInvoker)
    {
        $this->commandInvoker = $commandInvoker;

        return $this;
    }

    /** @return TextSanitizer */
    protected function getTextSanitizer()
    {
        if ($this->textSanitizer) {
            return $this->textSanitizer;
        }

        return $this->textSanitizer = new TextSanitizer();
    }

    /** @return LocationSanitizer */
    protected function getLocationSanitizer()
    {
        if ($this->locationSanitizer) {
            return $this->locationSanitizer;
        }

        return $this->locationSanitizer = new LocationSanitizer($this->getTextSanitizer());
    }

    /** @return ContentFactory */
    protected function getContentFactory()
    {
        if ($this->contentFactory) {
            return $this->contentFactory;
        }

        return $this->contentFactory = new ContentFactory();
    }
}
