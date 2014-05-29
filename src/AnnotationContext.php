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
 * The default annotation context.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class AnnotationContext implements AnnotationContextInterface
{
    /**
     * The context array from the reader.
     *
     * @since   0.1
     * @access  protected
     * @var     array
     */
    protected $context = array();

    /**
     * Constructor. Set up the context.
     *
     * @since   0.1
     * @access  public
     * @param   array $context
     * @return  void
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function hasContext($ctx)
    {
        $cur = $this->getContext();
        return ($cur & $ctx) === $cur;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        if (isset($this->context['function'])) {
            return AnnotationContextInterface::IS_FUNCTION;
        } elseif (isset($this->context['method'])) {
            return AnnotationContextInterface::IS_METHOD;
        } elseif (isset($this->context['property'])) {
            return AnnotationContextInterface::IS_PROPERTY;
        } else {
            return AnnotationContextInterface::IS_CLASS;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        if (isset($this->context['class'])) {
            return $this->context['class'];
        }

        $cls = null;
        if (isset($this->context['property'])) {
            $cls = $this->context['property']->getDeclaringClass();
        } elseif (isset($this->context['method'])) {
            $cls = $this->context['method']->getDeclaringClass();
        }

        if ($cls) {
            return $this->context['class'] = $cls;
        }

        throw new \BadMethodCallException(sprintf('%s called from a function context', __METHOD__));
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        if (isset($this->context['method'])) {
            return $this->context['method'];
        }

        throw new \BadMethodCallException(sprintf('%s called from a non-method context', __METHOD__));
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        if (isset($this->context['property'])) {
            return $this->context['property'];
        }

        throw new \BadMethodCallException(sprintf('%s called from a non-property context', __METHOD__));
    }

    /**
     * {@inheritdoc}
     */
    public function getFunction()
    {
        if (isset($this->context['function'])) {
            return $this->context['function'];
        }

        throw new \BadMethodCallException(sprintf('%s called from a non-function context', __METHOD__));
    }
}
