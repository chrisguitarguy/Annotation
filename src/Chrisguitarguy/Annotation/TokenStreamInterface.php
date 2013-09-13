<?php
/**
 * Annotation
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\Annotation;

/**
 * TokenStream's get return by the lexer and used by the parser.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface TokenStreamInterface extends \Iterator
{
    /**
     * Look at the token at the $pos
     *
     * @since   0.1
     * @access  public
     * @param   int $post
     * @throws  InvalidArgumentException
     * @return  Token
     */
    public function at($pos);

    /**
     * Peek ahead from the current position by $distance
     *
     * @since   0.1
     * @access  public
     * @param   int $distance
     * @return  Token
     */
    public function peek($distance=1);

    /**
     * Consume tokens (discard them) until reaching a token with the the type
     * $type
     *
     * @since   0.1
     * @access  public
     * @param   string $type
     * @return  boolean True of a token of $type was reached, false otherwise
     */
    public function skipUntil($type);

    /**
     * The current token should be of the type $type otherwise throw an
     * exception.
     *
     * @since   0.1
     * @access  public
     * @param   string $type
     * @throws  UnexpectedValueException
     * @return  void
     */
    public function expect($type);
}
