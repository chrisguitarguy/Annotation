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
 * A "registry" of annotations. Uses by readers to resolve annotation names
 * and create new instances of the annotation classes.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface AnnotationCollectionInterface
{
    /**
     * Add a new annotation to the collection.
     *
     * @since   0.1
     * @access  public
     * @param   string $name The annotation name as it will appear in doc blocks
     * @param   string $cls The class name of the annotation
     * @return  AnnotationCollectionInterface
     * @chainable
     */
    public function add($name, $cls);

    /**
     * Check to see the annotation exists in the collection.
     *
     * @since   0.1
     * @access  public
     * @param   string $name The annotation name as added by add (see above)
     * @return  boolean
     */
    public function has($name);

    /**
     * Remove an annotation.
     *
     * @since   0.1
     * @access  public
     * @param   string $name The annotation name as added by add (see above)
     * @return  boolean True if it was removed
     */
    public function remove($name);

    /**
     * Create an instance of the Annotation class registered at $name. If the
     * annotation is unknown, return null
     *
     * @since   0.1
     * @access  public
     * @param   string $name The annotation name (as registered with add)
     * @param   array $arguments
     * @param   array $context The context from which the annotation object is being created
     * @return  object|null
     */
    public function create($name, array $arguments, array $context);
}
