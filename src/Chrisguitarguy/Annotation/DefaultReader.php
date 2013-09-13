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
 * The default reader implementation.
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class DefaultReader implements ReaderInterface
{
    /**
     * The parser.
     *
     * @since   0.1
     * @access  protected
     * @var     ParserInterface
     */
    protected $parser;

    /**
     * The annotation collection.
     *
     * @since   0.1
     * @access  protected
     * @var     AnnotationCollectionInterface
     */
    protected $collection;

    /**
     * Constructor. Set up the collection and parser.
     *
     * @since   0.1
     * @access  public
     * @param   AnnotationCollectionInterface $collection
     * @param   ParserInterface $parser
     * @return  void
     */
    public function __construct(
        AnnotationCollectionInterface $collection=null,
        ParserInterface $parser=null
    ) {
        $this->setCollection($collection ?: new AnnotationCollection());
        $this->setParser($parser ?: new Parser());
    }

    /**
     * Set the collection.
     *
     * @since   0.1
     * @access  public
     * @param   AnnotationCollectionInterface $col
     * @return  ReaderInterface
     * @chainable
     */
    public function setCollection(AnnotationCollectionInterface $col)
    {
        $this->collection = $col;
        return $this;
    }

    /**
     * Get the collection.
     *
     * @since   0.1
     * @access  public
     * @return  AnnotationCollectionInterface
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set the parser.
     *
     * @since   0.1
     * @access  public
     * @param   ParserInterface $parser
     * @return  ReaderInterface
     * @chainable
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    /**
     * Get the parser.
     *
     * @since   0.1
     * @access  public
     * @return  ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * {@inheritdoc}
     */
    public function readProperty($cls, $name)
    {
        try {
            $ref = new \ReflectionProperty($cls, $name);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                sprintf('Could not create ReflectionProperty: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        return $this->fromDocBlock($ref->getDocComment());
    }

    /**
     * {@inheritdoc}
     */
    public function readProperties($cls, $filter=null)
    {
        $cls = $this->createReflectionClass($cls);

        $rv = array();
        $props = $filter ? $cls->getProperties($filter) : $cls->getProperties();
        foreach ($props as $prop) {
            $rv[$prop->getName()] = $this->fromDocBlock($prop->getDocComment());
        }

        return $rv;
    }

    /**
     * {@inheritdoc}
     */
    public function readClass($cls)
    {
        return $this->fromDocBlock($this->createReflectionClass($cls)->getDocComment());
    }

    /**
     * {@inheritdoc}
     */
    public function readMethod($cls, $meth)
    {
        try {
            $ref = new \ReflectionMethod($cls, $meth);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                sprintf('Unable to create ReflectionMethod: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        return $this->fromDocBlock($ref->getDocComment());
    }

    /**
     * {@inheritdoc}
     */
    public function readMethods($cls, $filter=null)
    {
        $ref = $this->createReflectionClass($cls);

        $rv = array();
        $methods = $filter ? $ref->getMethods($filter) : $ref->getMethods();
        foreach ($methods as $meth) {
            $rv[$meth->getName()] = $this->fromDocBlock($meth->getDocComment());
        }

        return $rv;
    }

    /**
     * {@inheritdoc}
     */
    public function readFunction($func)
    {
        try {
            $ref = new \ReflectionFunction($func);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                sprintf('Could not create ReflectionFunction: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        return $this->fromDocBlock($ref->getDocComment());
    }

    protected function createReflectionClass($cls)
    {
        try {
            return new \ReflectionClass($cls);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                sprintf('Could not create reflection class: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    protected function fromDocblock($docblock)
    {
        $annotations = $this->getParser()->parse($docblock);

        $col = $this->getCollection();
        $rv = array();
        foreach ($annotations as $annot) {
            list($name, $arguments) = $annot;

            if ($obj = $col->create($name, $arguments)) {
                $rv[] = $obj;
            }
        }

        return $rv;
    }
}
