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
 * The default annotation collection. If you need some sort of custom annotation
 * object creation strategy subclass and override `create`
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AnnotationCollection implements AnnotationCollectionInterface
{
    /**
     * The array of annotations in $name => $cls pairs.
     *
     * @since   0.1
     * @access  protected
     * @var     array
     */
    protected $registry = array();

    /**
     * Constructor. Set up the registry of annotations.
     *
     * @since   0.1
     * @access  public
     * @param   array $registry An array of $name => $cls pairs
     * @return  void
     */
    public function __construct(array $registry=array())
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $cls)
    {
        $this->registry[$name] = $cls;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->registry);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->registry[$name]);
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, array $arguments, array $context)
    {
        if ($this->has($name)) {
            return new $this->registry[$name]($arguments, new AnnotationContext($context));
        }

        return null;
    }
}
