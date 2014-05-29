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
 * A collection of constants.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
final class Tokens
{
    const T_AT              = 'T_AT';
    const T_IDENTIFIER      = 'T_IDENTIFIER';


    const T_OPEN_PAREN      = 'T_OPEN_PAREN';
    const T_CLOSE_PAREN     = 'T_CLOSE_PAREN';
    const T_EQUALS          = 'T_EQUALS';
    const T_COMMA           = 'T_COMMA';
    const T_COLON           = 'T_COLON';
    const T_OPEN_BRACKET    = 'T_OPEN_BRACKET';
    const T_CLOSE_BRACKET   = 'T_CLOSE_BRACKET';
    const T_OPEN_BRACE      = 'T_OPEN_BRACE';
    const T_CLOSE_BRACE     = 'T_CLOSE_BRACE';


    const T_FLOAT           = 'T_FLOAT';
    const T_INT             = 'T_INT';
    const T_STRING          = 'T_STRING';
    const T_TRUE            = 'T_TRUE';
    const T_FALSE           = 'T_FALSE';
    const T_NULL            = 'T_NULL';


    const T_WHITESPACE      = 'T_WHITESPACE';
    const T_EOF             = 'T_EOF';

    // @codeCoverageIgnoreStart
    private function __construct()
    {
        // noop
    }
    // @codeCoverageIgnoreEnd
}
