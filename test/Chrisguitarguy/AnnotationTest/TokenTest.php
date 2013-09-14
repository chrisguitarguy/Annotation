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
use Chrisguitarguy\Annotation\Tokens;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenProperties()
    {
        $token = new Token(Tokens::T_IDENTIFIER, 'here', 1);

        $this->assertEquals(Tokens::T_IDENTIFIER, $token->name);
        $this->assertEquals('here', $token->value);
        $this->assertEquals(1, $token->position);
    }

    public function testTestWithSingleType()
    {
        $token = new Token(Tokens::T_IDENTIFIER, 'here', 1);

        $this->assertFalse($token->test(Tokens::T_EOF));
        $this->assertTrue($token->test(Tokens::T_IDENTIFIER));
    }

    public function testTestWithArray()
    {
        $token = new Token(Tokens::T_IDENTIFIER, 'here', 1);

        $this->assertFalse($token->test(array(Tokens::T_EOF, Tokens::T_WHITESPACE)));
        $this->assertTrue($token->test(array(Tokens::T_IDENTIFIER, Tokens::T_EOF)));
        
    }
}
