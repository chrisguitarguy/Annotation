<?php
/**
 * Annotation
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\Annotation;

interface LexerInterface
{
    /**
     * Turn an input (doc comment) into a TokenStream.
     *
     * @since   0.1
     * @access  public
     * @param   string $input
     * @return  Chrisguitarguy\SimpleAnnotation\TokenStream
     */
    public function lex($input);
}
