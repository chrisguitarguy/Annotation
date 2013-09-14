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
            "/**
             * This is a docblock just a lonesome @ sign should not cause errors
             *
             * @Annotation(argument={one : \"here\", 'two': [-1, 1, 1.0, [-2.1], {},],})
             *
             * @Ident(argument=true, again=false, nope=null)
             * @param   string
             */"
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
        $this->assertEquals('here', $arg['one']);
        $this->assertArrayHasKey('two', $arg);
        $this->assertInternalType('array', $arg['two']);
        $this->assertCount(5, $arg['two']);
    }

    protected function setUp()
    {
        $this->parser = new Parser();
    }
}
