<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

class BaseAnnotationTest extends \PHPUnit_Framework_TestCase
{
    private $obj, $context, $args;

    public function testGetContext()
    {
        $this->assertSame($this->context, $this->obj->getContext());
    }

    public function testOffsetGetExists()
    {
        $this->assertFalse(isset($this->obj['nope']));
        $this->assertTrue(isset($this->obj['one']));
        $this->assertEquals('two', $this->obj['one']);
    }

    /**
     * @expectedException LogicException
     */
    public function testOffsetUnset()
    {
        unset($this->obj['one']);
    }

    /**
     * @expectedException LogicException
     */
    public function testOffsetSet()
    {
        $this->obj['two'] = 'three';
    }

    protected function setUp()
    {
        $this->args = array('one' => 'two');
        $this->context = $this->getMock('Chrisguitarguy\\Annotation\\AnnotationContextInterface');

        $this->obj = $this->getMockForAbstractClass(
            'Chrisguitarguy\\Annotation\\BaseAnnotation',
            array($this->args, $this->context)
        );
    }
}
