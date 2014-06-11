<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Converter\PhpContentConverter;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\Php\Token;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use PhpSpec\ObjectBehavior;

class PhpSearchStrategySpec extends ObjectBehavior
{
    const CLASS_NAME = 'AppKernel';
    const METHOD_NAME = 'registerBundles';
    const FILENAME = '%s/tests/fixtures/sources/AppKernel.php';

    private $tokenBuilder;

    function let(File $file)
    {
        $rootPath = __DIR__.'/../../../../..';
        $filename = sprintf(self::FILENAME, $rootPath);
        $content = file_get_contents($filename);
        $file->getFilename()->willReturn($filename);
        $file->read()->willReturn($content);

        $this->tokenBuilder = new TokenBuilder();
        $converter = new PhpContentConverter($this->tokenBuilder);

        $this->beConstructedWith($converter);
    }

    function it_is_a_search_strategy()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Search\SearchStrategy');
    }

    function it_supports_php_criterion()
    {
        $rawTokens = token_get_all('<?php echo 42;');
        $tokens = $this->tokenBuilder->buildFromRaw($rawTokens);
        $emptyArray = array();
        $rawLine = "Sir Bedevere: Good. Now, why do witches burn?\n";
        $lineNumber = 42;
        $rubishArray = array(23, 1337);

        $this->supports($tokens)->shouldBe(true);
        $this->supports($emptyArray)->shouldBe(true);
        $this->supports($rawLine)->shouldBe(false);
        $this->supports($rubishArray)->shouldBe(false);
        $this->supports($lineNumber)->shouldBe(false);
    }

    function it_checks_tokens_presence(File $file)
    {
        $presentTokens = $this->tokenBuilder->buildClass('AppKernel');
        $rawTokens = token_get_all('<?php $i++;');
        $absentTokens = $this->tokenBuilder->buildFromRaw($rawTokens);

        $this->has($file, $presentTokens)->shouldBe(true);
        $this->has($file, $absentTokens)->shouldBe(false);
    }

    function it_finds_next_occurences(File $file)
    {
        $previousToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $nextLineNumber = 15;
        $nextToken = $this->tokenBuilder->buildMethod('registerBundles');

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindNext($file, $previousToken);
        $this->shouldThrow($exception)->duringFindNext($file, $currentToken);
        $this->findNext($file, $nextToken)->shouldBe($nextLineNumber);
    }

    function it_finds_previous_occurences(File $file)
    {
        $previousLineNumber = 0;
        $previousToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $nextToken = $this->tokenBuilder->buildMethod('registerBundles');

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindPrevious($file, $nextToken);
        $this->shouldThrow($exception)->duringFindPrevious($file, $currentToken);
        $this->findPrevious($file, $previousToken)->shouldBe($previousLineNumber);
    }
}
