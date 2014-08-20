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

use Gnugat\Redaktilo\Search\Php\Token;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use Gnugat\Redaktilo\Service\LineBreak;
use Gnugat\Redaktilo\Service\TextToPhpConverter;
use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class PhpSearchStrategySpec extends ObjectBehavior
{
    const CLASS_NAME = 'AppKernel';
    const METHOD_NAME = 'registerBundles';
    const FILENAME = '%s/tests/fixtures/sources/AppKernel.php';

    private $tokenBuilder;

    function let(Text $text)
    {
        $rootPath = __DIR__.'/../../../../..';
        $filename = sprintf(self::FILENAME, $rootPath);
        $content = file_get_contents($filename);
        $lineBreak = new LineBreak();
        $lineBreak = $lineBreak->detect($content);
        $lines = explode($lineBreak, $content);
        $text->getLineBreak()->willReturn($lineBreak);
        $text->getLines()->willReturn($lines);

        $this->tokenBuilder = new TokenBuilder();
        $converter = new TextToPhpConverter($this->tokenBuilder);

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

    function it_finds_above_occurences(Text $text)
    {
        $aboveLineNumber = 0;
        $aboveToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $belowToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findAbove($text, $belowToken, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $aboveToken, $currentLineNumber)->shouldBe($aboveLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $belowToken)->shouldBe(false);
        $this->findAbove($text, $currentToken)->shouldBe(false);
        $this->findAbove($text, $aboveToken)->shouldBe($aboveLineNumber);
    }

    function it_finds_below_occurences(Text $text)
    {
        $aboveToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $belowLineNumber = 15;
        $belowToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findBelow($text, $aboveToken, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $belowToken, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findBelow($text, $aboveToken)->shouldBe(false);
        $this->findBelow($text, $currentToken)->shouldBe(false);
        $this->findBelow($text, $belowToken)->shouldBe($belowLineNumber);
    }
}
