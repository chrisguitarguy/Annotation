<?php
/**
 * Annotations
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\Annotation;

/**
 * An abstract base class that may be used for annotation classes. Provides
 * a getters/setters for contexts as well as implementing ArrayAccess to check
 * on arguments.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
abstract class BaseAnnotation implements \ArrayAccess
{
    /**
     * The "arguments" from the annotation. Accessed via the ArrayAccess
     * interface.
     *
     * @since   0.1
     * @access  protected
     * @var     array
     */
    protected $arguments = array();

    /**
     * The annotation context.
     *
     * @since   0.1
     * @access  protected
     * @var     AnnotationContextInterface
     */
    protected $context;

    /**
     * Construct. Set up the arguments and context.
     *
     * @since   0.1
     * @access  public
     * @param   array $arguments
     * @param   AnnotationContextInterface $context
     * @return  void
     */
    public function __construct(array $arguments, AnnotationContextInterface $context)
    {
        $this->arguments = $arguments;
        $this->context = $context;
    }

    /**
     * Get the context.
     *
     * @since   0.1
     * @access  public
     * @return  AnnotationContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }

    /** ArrayAccess ***********************************************************/

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->arguments);
    }

    public function offsetGet($key)
    {
        return $this->arguments[$key];
    }

    public function offsetUnset($key)
    {
        throw new \LogicException('Annotations are immutable');
    }

    public function offsetSet($key, $val)
    {
        throw new \LogicException('Annotations are immutable');
    }
}
