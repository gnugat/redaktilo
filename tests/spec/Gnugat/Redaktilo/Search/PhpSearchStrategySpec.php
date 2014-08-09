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

    function it_finds_above_occurences(File $file)
    {
        $aboveLineNumber = 0;
        $aboveToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $underToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findAbove($file, $underToken, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $aboveToken, $currentLineNumber)->shouldBe($aboveLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($file, $underToken)->shouldBe(false);
        $this->findAbove($file, $currentToken)->shouldBe(false);
        $this->findAbove($file, $aboveToken)->shouldBe($aboveLineNumber);
    }

    function it_finds_under_occurences(File $file)
    {
        $aboveToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $underLineNumber = 15;
        $underToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findUnder($file, $aboveToken, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $underToken, $currentLineNumber)->shouldBe($underLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findUnder($file, $aboveToken)->shouldBe(false);
        $this->findUnder($file, $currentToken)->shouldBe(false);
        $this->findUnder($file, $underToken)->shouldBe($underLineNumber);
    }
}
