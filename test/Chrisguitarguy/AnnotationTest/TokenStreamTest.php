<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\Token;
use Chrisguitarguy\Annotation\TokenStream;

class TokenStreamTest extends \PHPUnit_Framework_TestCase
{
    private $stream, $tokens;

    public function testCountable()
    {
        $this->assertEquals(2, count($this->stream));
    }

    public function testIterator()
    {
        foreach ($this->stream as $idx => $token) {
            $this->assertSame($this->tokens[$idx], $token);
        }

        // make sure rewinding works
        foreach ($this->stream as $idx => $token) {
            $this->assertSame($this->tokens[$idx], $token);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAtWithOutOfRange()
    {
        $this->stream->at(10);
    }

    public function testAt()
    {
        $this->assertSame($this->tokens[1], $this->stream->at(1));
    }

    public function testPeekWithOutOfRange()
    {
        $this->assertSame($this->tokens[1], $this->stream->peek(20));
    }

    public function testPeak()
    {
        $this->assertSame($this->tokens[1], $this->stream->peek());
    }

    public function testSkipUntilWithNotFound()
    {
        $found = $this->stream->skipUntil('a_unknown_type');

        $this->assertFalse($found);
        $this->assertFalse($this->stream->valid());
    }

    public function testSkipUntil()
    {
        $found = $this->stream->skipUntil('type2');

        $this->assertTrue($found);
        $this->assertSame($this->tokens[1], $this->stream->current());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testExpect()
    {
        $this->stream->expect('not_there');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testExpectWithConsumedStream()
    {
        while ($this->stream->valid()) {
            $this->stream->next();
        }

        $this->stream->expect('typer');
    }

    public function testExpectDoesNothing()
    {
        $this->stream->expect('type');
    }

    protected function setUp()
    {
        $this->tokens = array(
            new Token('type', 'value', 1),
            new Token('type2', 'value2', 2),
        );

        $this->stream = new TokenStream($this->tokens);
    }
}
