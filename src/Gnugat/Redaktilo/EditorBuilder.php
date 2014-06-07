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
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Replace\ReplaceEngine;
use Gnugat\Redaktilo\Replace\ReplaceStrategy;
use Gnugat\Redaktilo\Converter\ContentConverter;
use Gnugat\Redaktilo\Converter\LineContentConverter;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * @author Wouter J <wouter@wouterj.nl>
 *
 * @api
 */
class EditorBuilder
{
    /** @var ContentConverter */
    private $converter;

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

    /** @return ContentConverter */
    protected function getConverter()
    {
        if ($this->converter) {
            return $this->converter;
        }

        return $this->converter = new LineContentConverter();
    }

    /** @return SearchEngine */
    protected function getSearchEngine()
    {
        if ($this->searchEngine) {
            return $this->searchEngine;
        }

        $engine = new SearchEngine();
        $converter = $this->getConverter();

        $engine->registerStrategy(new Search\LineRegexSearchStrategy($converter));
        $engine->registerStrategy(new Search\SubstringSearchStrategy($converter));
        $engine->registerStrategy(new Search\LineNumberSearchStrategy($converter));

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
        $converter = $this->getConverter();

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
