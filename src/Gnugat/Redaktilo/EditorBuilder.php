<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Replace\ReplaceEngine;
use Gnugat\Redaktilo\Converter\PhpContentConverter;
use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\Replace\ReplaceStrategy;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * @author Wouter J <wouter@wouterj.nl>
 *
 * @api
 */
class EditorBuilder
{
    /** @var LineContentConverter */
    private $lineConverter;

    /** @var PhpContentConverter */
    private $phpConverter;

    /** @var SearchEngine|null */
    private $searchEngine;

    /** @var SearchStrategy[] */
    private $searchStrategies = array();

    /** @var ReplaceEngine|null */
    private $replaceEngine;

    /** @var ReplaceStrategy[] */
    private $replaceStrategies = array();

    /** @var Filesystem */
    private $filesystem;

    protected function getLineConverter()
    {
        if ($this->lineConverter) {
            return $this->lineConverter;
        }

        return $this->lineConverter = new LineContentConverter();
    }

    protected function getPhpConverter()
    {
        if ($this->phpConverter) {
            return $this->phpConverter;
        }
        $tokenBuilder = new TokenBuilder();

        return $this->phpConverter = new PhpContentConverter($tokenBuilder);
    }

    /** @return SearchEngine */
    protected function getSearchEngine()
    {
        if ($this->searchEngine) {
            return $this->searchEngine;
        }

        $engine = new SearchEngine();
        $lineConverter = $this->getLineConverter();
        $phpConverter = $this->getPhpConverter();

        $engine->registerStrategy(new Search\PhpSearchStrategy($phpConverter));
        $engine->registerStrategy(new Search\LineRegexSearchStrategy($lineConverter));
        $engine->registerStrategy(new Search\SubstringSearchStrategy($lineConverter));
        $engine->registerStrategy(new Search\LineNumberSearchStrategy($lineConverter));

        foreach ($this->searchStrategies as $strategy) {
            $engine->registerStrategy($strategy);
        }

        return $engine;
    }

    /** @return ReplaceEngine */
    protected function getReplaceEngine()
    {
        if ($this->replaceEngine) {
            return $this->replaceEngine;
        }

        $engine = new ReplaceEngine();
        $converter = $this->getLineConverter();

        $engine->registerStrategy(new Replace\LineReplaceStrategy($converter));

        foreach ($this->replaceStrategies as $strategy) {
            $engine->registerStrategy($strategy);
        }

        return $engine;
    }

    /** @return Filesystem */
    protected function getFilesystem()
    {
        if ($this->filesystem) {
            return $this->filesystem;
        }

        return new Filesystem(new SymfonyFilesystem());
    }

    /**
     * @return Editor
     *
     * @api
     */
    public function getEditor()
    {
        return new Editor($this->getFilesystem(), $this->getSearchEngine(), $this->getReplaceEngine());
    }

    /**
     * @param SearchStrategy $searchStrategy
     *
     * @return $this
     *
     * @api
     */
    public function addSearchStrategy(SearchStrategy $searchStrategy)
    {
        $this->searchStrategies[] = $searchStrategy;

        return $this;
    }

    /**
     * @param ReplaceStrategy $replaceStrategy
     *
     * @return $this
     *
     * @api
     */
    public function addReplaceStrategy(ReplaceStrategy $replaceStrategy)
    {
        $this->replaceStrategies[] = $replaceStrategy;

        return $this;
    }

    /**
     * @param SearchEngine $searchEngine
     *
     * @return $this
     *
     * @api
     */
    public function setSearchEngine(SearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;

        return $this;
    }

    /**
     * @param ReplaceEngine $replaceEngine
     *
     * @return $this
     *
     * @api
     */
    public function setReplaceEngine(ReplaceEngine $replaceEngine)
    {
        $this->replaceEngine = $replaceEngine;

        return $this;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return $this
     *
     * @api
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }
}
