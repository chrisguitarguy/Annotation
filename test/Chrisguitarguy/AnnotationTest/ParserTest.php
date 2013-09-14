<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser, $lexer;

    public function testSuccessfulParse()
    {
        $annotations = $this->parser->parse(
            '/**
             * This is a docblock just a lonesome @ sign should\\\'t cause errors
             *
             * @Annotation(argument={one : "he\"re", \'tw\\\'o\': [-1, 1, 1.0, [-2.1], {},],})
             *
             * @Ident(argument=true, again=false, nope=null)
             * @param   string
             */'
         );

        $this->assertInternalType('array', $annotations);
        $this->assertCount(2, $annotations);
        $this->assertCount(2, $annotations[0]);
        $this->assertCount(2, $annotations[1]);
        $this->assertInternalType('array', $annotations[0]);
        $this->assertInternalType('string', $annotations[0][0]);
        $this->assertInternalType('array', $annotations[0][1]);

        $args = $annotations[0][1];

        $this->assertArrayHasKey('argument', $args);

        $arg = $args['argument'];
        $this->assertInternalType('array', $arg);
        $this->assertArrayHasKey('one', $arg);
        $this->assertEquals('he"re', $arg['one']);
        $this->assertArrayHasKey("tw'o", $arg);
        $this->assertInternalType('array', $arg["tw'o"]);
        $this->assertCount(5, $arg["tw'o"]);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseWithUnclosedParen()
    {
        $this->parser->parse('@Annot(arg="one"');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseWithUnclosedBracket()
    {
        $this->parser->parse('@Annot(arg=[)');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseWithUnclosedBrace()
    {
        $this->parser->parse('@annot(arg={)');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseWithMissingComma()
    {
        $this->parser->parse('@annot(arg =    "one" arg2="three")');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testParseWithMissingEquals()
    {
        $this->parser->parse('@annot(arge "one")');
    }

    protected function setUp()
    {
        $this->parser = new Parser();
    }
}
