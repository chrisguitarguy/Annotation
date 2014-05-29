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
 * Parse an input into something useful.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface ParserInterface
{
    /**
     * Parse "input" (a docblock) and return an array of annotations.
     *
     * @since   0.1
     * @access  public
     * @param   string $input
     * @return  array[] array(array($annotation_name, $arguments), ...)
     */
    public function parse($input);
}
