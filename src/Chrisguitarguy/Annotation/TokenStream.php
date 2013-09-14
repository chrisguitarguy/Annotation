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
 * The default token stream.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class TokenStream implements TokenStreamInterface
{
    /**
     * The array of tokens returned from the lexer.
     *
     * @since   0.1
     * @access  protected
     * @var     Token[]
     */
    protected $stream = array();

    /**
     * Our current position in the stream.
     *
     * @since   0.1
     * @access  protected
     * @var     int
     */
    protected $position = 0;

    /**
     * Constructor. Set up our array of tokens.
     *
     * @since   0.1
     * @access  public
     * @param   array $stream
     * @return  void
     */
    public function __construct(array $stream)
    {
        $this->stream = $stream;
    }

    /** Iterator **************************************************************/

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->stream[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->stream[$this->position]);
    }

    /** Countable *************************************************************/

    public function count()
    {
        return count($this->stream);
    }

    /** TokenStreamInterface **************************************************/

    /**
     * {@inheritdoc}
     */
    public function at($pos)
    {
        if (!isset($this->stream[$pos])) {
            throw new \InvalidArgumentException(sprintf('Position "%d" is invalid', $pos));
        }

        return $this->stream[$pos];
    }

    /**
     * {@inheritdoc}
     */
    public function peek($distance=1)
    {
        if (isset($this->stream[$this->position + $distance])) {
            return $this->stream[$this->position + $distance];
        }

        // if we try to peak beyond the end of the stream return the last token
        return $this->stream[count($this->stream) - 1];
    }

    /**
     * {@inheritdoc}
     */
    public function skipUntil($type)
    {
        $found = false;
        while ($this->valid()) {
            if ($this->current()->test($type)) {
                $found = true;
                break;
            }

            $this->next();
        }

        return $found;
    }

    /**
     * {@inheritdoc}
     */
    public function expect($type)
    {
        if (!$this->valid() || !$this->current()->test($type)) {
            throw new \UnexpectedValueException(sprintf(
                'Exepcted token with the type "%s", got "%s" with value "%s"',
                implode('|', (array)$type),
                $this->current()->name,
                $this->current()->value
            ));
        }
    }
}
