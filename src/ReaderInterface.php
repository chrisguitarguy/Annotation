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
 * Read different things doc comments and return an array of annotation objects.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
interface ReaderInterface
{
    /**
     * Read the annotations from a property.
     *
     * @since   0.1
     * @access  public
     * @param   string $cls
     * @param   string $prop
     * @throws  InvalidArgumentException if the class or property doesn't exist
     * @return  array
     */
    public function readProperty($cls, $name);

    /**
     * Read all properties from a class and return an array of
     * $prop_name => object[] pairs.
     *
     * @since   0.1
     * @access  public
     * @param   string $cls
     * @param   int $filter A combination of the the ReflectionProperty constants
     * @throws  InvalidArgumentException if the class doesn't exist
     * @return  array
     */
    public function readProperties($cls, $filter=null);

    /**
     * Read the annotations from a class doc block.
     *
     * @since   0.1
     * @access  public
     * @param   string $cls
     * @throws  InvalidArgumentException if the class doesn't exist
     * @return  array
     */
    public function readClass($cls);

    /**
     * Read a class method's annotations and return them as an array.
     *
     * @since   0.1
     * @access  public
     * @param   string $cls
     * @param   string $meth
     * @throws  InvalidArgumentException if the class or method doesn't exist
     * @return  array
     */
    public function readMethod($cls, $meth);

    /**
     * Read all a classes methods and return their annotations in $method =>
     * object[] pairs.
     *
     * @since   0.1
     * @access  public
     * @param   string $cls
     * @param   int $filter A combination of the ReflectionMethod constants
     * @throws  InvalidArgumentException if the class doesn't exist
     * @return  array
     */
    public function readMethods($cls, $filter=null);

    /**
     * Read a functions annotations and return them.
     *
     * @since   0.1
     * @access  public
     * @param   string $func
     * @throws  InvalidArgumentException if the function doesn't exist
     * @return  array
     */
    public function readFunction($func);
}
