<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\Lexer;

class LexterTest extends \PHPUnit_Framework_TestCase
{
    private $lexer;

    public function testLex()
    {
        $stream = $this->lexer->lex('@Indent(argument={key: 1, "value": [1.2, -3]},)');

        $this->assertInstanceOf('Chrisguitarguy\\Annotation\\TokenStreamInterface', $stream);
        $this->assertEquals(25, count($stream));
        $this->assertInstanceOf('Chrisguitarguy\\Annotation\\Token', $stream->at(0));
    }

    public function testLexWithNoTokens()
    {
        $stream = $this->lexer->lex('/***"/');

        $this->assertInstanceOf('Chrisguitarguy\\Annotation\\TokenStreamInterface', $stream);
        $this->assertEquals(1, count($stream));
        $this->assertInstanceOf('Chrisguitarguy\\Annotation\\Token', $stream->at(0));
        $this->assertEquals('T_EOF', $stream->at(0)->name);
    }

    protected function setUp()
    {
        $this->lexer = new Lexer();
    }
}
