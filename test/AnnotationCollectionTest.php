<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\AnnotationCollection;

class TestAnnotation { }

class AnnotationCollectionTest extends \PHPUnit_Framework_TestCase
{
    private $collection;

    public function testAddHasRemove()
    {
        $this->assertFalse($this->collection->has('Annot'));
        $this->assertFalse($this->collection->remove('Annot'));
        $this->assertSame($this->collection, $this->collection->add('Annot', 'Annotation'));
        $this->assertTrue($this->collection->has('Annot'));
        $this->assertTrue($this->collection->remove('Annot'));
        $this->assertFalse($this->collection->has('Annot'));
    }

    /**
     * @depends testAddHasRemove
     */
    public function testCreate()
    {
        $this->collection->add('Annot', __NAMESPACE__ . '\\TestAnnotation');

        $this->assertInstanceOf(__NAMESPACE__ . '\\TestAnnotation', $this->collection->create(
            'Annot',
            array(),
            array()
        ));
    }

    public function testCreateWithoutValidCalls()
    {
        $this->assertNull($this->collection->create('Annot', array(), array()));
    }

    protected function setUp()
    {
        $this->collection = new AnnotationCollection();
    }
}
