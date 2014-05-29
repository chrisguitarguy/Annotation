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
 * Represent a single token from our lexer. Just a collection of public
 * properties and a test method.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class Token
{
    /**
     * The tokens name or type. T_*
     *
     * @since   0.1
     * @access  public
     * @var     string
     */
    public $name;

    /**
     * The tokens value.
     *
     * @since   0.1
     * @access  public
     * @var     string
     */
    public $value;

    /**
     * The tokens position in the docblock. Not really used.
     *
     * @since   0.1
     * @access  public
     * @var     int
     */
    public $position;

    /**
     * Constructor. Set the name, value, and position.
     *
     * @since   0.1
     * @access  public
     * @param   string $name
     * @param   string $value
     * @param   int $position
     * @return  void
     */
    public function __construct($name, $value, $position)
    {
        $this->name = $name;
        $this->value = $value;
        $this->position = $position;
    }

    /**
     * Test token against a type.
     *
     * @since   0.1
     * @access  public
     * @param   string $expected
     * @return  boolean
     */
    public function test($expected)
    {
        return is_array($expected) ? in_array($this->name, $expected, true) : $expected === $this->name;
    }
}
