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
 * Annotation context's are used by readers to pass information about where a
 * given annotation was found when it was read. Context's give you access to
 * information about what "type" of context the the annotaiton was found in 
 * (class|method|property|function) as well as access to the reflection objects
 * around those contexts.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface AnnotationContextInterface
{
    const IS_CLASS      = 1;
    const IS_METHOD     = 2;
    const IS_PROPERTY   = 4;
    const IS_FUNCTION   = 8;

    /**
     * Check to see if context is one of the four IS_* constants above.
     *
     * @since   0.1
     * @access  public
     * @param   int $ctx
     * @return  boolean
     */
    public function hasContext($ctx);

    /**
     * Get the context. One of the IS_* constants above.
     *
     * @since   0.1
     * @access  public
     * @return  int
     */
    public function getContext();

    /**
     * The the reflection class for the annotation. Works if the context
     * is class, method, or property.
     *
     * @since   0.1
     * @access  public
     * @throws  BadMethodCallException when called from a function context
     * @return  RefelectionClass
     */
    public function getClass();

    /**
     * Get the reflection method for the annotation.
     *
     * @since   0.1
     * @access  public
     * @throws  BadMethodCallException when called from anything but a method context
     * @return  ReflectionMethod
     */
    public function getMethod();

    /**
     * Get the reflection property for the annotation.
     *
     * @since   0.1
     * @access  public
     * @throws  BadMethodCallException when called from anything but a property context
     * @return  ReflectionProperty
     */
    public function getProperty();

    /**
     * Get the reflection function for the annotation.
     *
     * @since   0.1
     * @access  public
     * @throws  BadMethodCallException when called from anything but a function context
     * @return  ReflectionFunction
     */
    public function getFunction();
}
