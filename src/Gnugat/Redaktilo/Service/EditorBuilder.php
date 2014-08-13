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

use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Command\Command;
use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Command\LineInsertAboveCommand;
use Gnugat\Redaktilo\Command\LineInsertUnderCommand;
use Gnugat\Redaktilo\Command\LineRemoveCommand;
use Gnugat\Redaktilo\Command\LineReplaceCommand;
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
    /** @var LineBreak */
    private $lineBreak;

    /** @var TextToPhpConverter */
    private $phpConverter;

    /** @var SearchEngine|null */
    private $searchEngine;

    /** @var SearchStrategy[] */
    private $searchStrategies = array();

    /** @var CommandInvoker|null */
    private $commandInvoker;

    /** @var Command[] */
    private $commands = array();

    /** @var TextFactory */
    private $textFactory;

    /** @var FileFactory */
    private $fileFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @return LineBreak */
    protected function getLineBreak()
    {
        if ($this->lineBreak) {
            return $this->lineBreak;
        }

        return $this->lineBreak = new LineBreak();
    }

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

        $engine->registerStrategy(new PhpSearchStrategy($phpConverter));
        $engine->registerStrategy(new LineRegexSearchStrategy());
        $engine->registerStrategy(new SameSearchStrategy());
        $engine->registerStrategy(new LineNumberSearchStrategy());

        foreach ($this->searchStrategies as $strategy) {
            $engine->registerStrategy($strategy);
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

        $commandInvoker->addCommand(new LineInsertAboveCommand());
        $commandInvoker->addCommand(new LineInsertUnderCommand());
        $commandInvoker->addCommand(new LineReplaceCommand());
        $commandInvoker->addCommand(new LineRemoveCommand());

        foreach ($this->commands as $command) {
            $commandInvoker->addCommand($command);
        }

        return $commandInvoker;
    }

    /** @return TextFactory */
    protected function getTextFactory()
    {
        if ($this->textFactory) {
            return $this->textFactory;
        }

        return new TextFactory($this->getLineBreak());
    }

    /** @return FileFactory */
    protected function getFileFactory()
    {
        if ($this->fileFactory) {
            return $this->fileFactory;
        }

        return new FileFactory($this->getLineBreak());
    }

    /** @return Filesystem */
    protected function getFilesystem()
    {
        if ($this->filesystem) {
            return $this->filesystem;
        }

        return new Filesystem($this->getFileFactory(), new SymfonyFilesystem());
    }

    /** @return Editor */
    public function getEditor()
    {
        return new Editor(
            $this->getTextFactory(),
            $this->getFilesystem(),
            $this->getSearchEngine(),
            $this->getCommandInvoker()
        );
    }

    /**
     * @param SearchStrategy $searchStrategy
     *
     * @return $this
     */
    public function addSearchStrategy(SearchStrategy $searchStrategy)
    {
        $this->searchStrategies[] = $searchStrategy;

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
}
