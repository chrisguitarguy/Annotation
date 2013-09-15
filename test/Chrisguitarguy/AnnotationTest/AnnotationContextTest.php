<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\AnnotationContext;
use Chrisguitarguy\Annotation\AnnotationContextInterface;

class AnnotationContextTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContextWithProperty()
    {
        $c = new AnnotationContext(array(
            'property'  => $this->getPropertyMock(),
            'class'     => $this->getClassMock(),
        ));

        $this->assertEquals(AnnotationContextInterface::IS_PROPERTY, $c->getContext());
    }

    public function testGetContextWithMethod()
    {
        $c = new AnnotationContext(array(
            'method'    => $this->getMethodMock(),
            'class'     => $this->getClassMock(),
        ));

        $this->assertEquals(AnnotationContextInterface::IS_METHOD, $c->getContext());
    }

    public function testGetContextWithFunction()
    {
        $c = new AnnotationContext(array(
            'function'  => $this->getFunctionMock(),
        ));

        $this->assertEquals(AnnotationContextInterface::IS_FUNCTION, $c->getContext());
    }

    public function testGetContextWithClass()
    {
        $c = new AnnotationContext(array(
            'class'     => $this->getClassMock()
        ));

        $this->assertEquals(AnnotationContextInterface::IS_CLASS, $c->getContext());
    }

    public function testHasContext()
    {
        $c = new AnnotationContext(array(
            'property'  => $this->getPropertyMock(),
        ));

        $this->assertTrue($c->hasContext(AnnotationContextInterface::IS_PROPERTY));
        $this->assertFalse($c->hasContext(AnnotationContextInterface::IS_CLASS));

        $this->assertTrue($c->hasContext(
            AnnotationContextInterface::IS_CLASS | AnnotationContextInterface::IS_PROPERTY
        ));

        $this->assertFalse($c->hasContext(
            AnnotationContextInterface::IS_CLASS | AnnotationContextInterface::IS_METHOD
        ));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetMethodWithoutMethod()
    {
        (new AnnotationContext(array()))->getMethod();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetPropertyWithoutProperty()
    {
        (new AnnotationContext(array()))->getProperty();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetFunctionWithoutFunction()
    {
        (new AnnotationContext(array()))->getFunction();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testGetClassWithoutClass()
    {
        (new AnnotationContext(array()))->getClass();
    }

    public function testGetClassWithClass()
    {
        $ref = $this->getClassMock();

        $c = new AnnotationContext(array('class' => $ref));

        $this->assertSame($ref, $c->getClass());
    }

    public function testGetClassWithProperty()
    {
        $prop = $this->getPropertyMock();
        $cls = $this->getClassMock();

        $prop->expects($this->once())
            ->method('getDeclaringClass')
            ->will($this->returnValue($cls));

        $c = new AnnotationContext(array(
            'property'  => $prop,
        ));

        $this->assertSame($cls, $c->getClass());
    }

    public function testGetClassWithMethod()
    {
        $meth = $this->getMethodMock();
        $cls = $this->getClassMock();

        $meth->expects($this->once())
            ->method('getDeclaringClass')
            ->will($this->returnValue($cls));

        $c = new AnnotationContext(array(
            'method'    => $meth,
        ));

        $this->assertSame($cls, $c->getClass());
    }

    public function testGetMethod()
    {
        $meth = $this->getMethodMock();

        $c = new AnnotationContext(array(
            'method'    => $meth,
        ));

        $this->assertSame($meth, $c->getMethod());
    }

    public function testGetFunction()
    {
        $func = $this->getFunctionMock();

        $c = new AnnotationContext(array(
            'function'  => $func,
        ));

        $this->assertSame($func, $c->getFunction());
    }

    public function testGetProperty()
    {
        $prop = $this->getPropertyMock();

        $c = new AnnotationContext(array(
            'property'  => $prop,
        ));

        $this->assertSame($prop, $c->getProperty());
    }

    private function getPropertyMock()
    {
        return $this->getMockBuilder('ReflectionProperty')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getClassMock()
    {
        return $this->getMockBuilder('ReflectionClass')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMethodMock()
    {
        return $this->getMockBuilder('ReflectionMethod')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getFunctionMock()
    {
        return $this->getMockBuilder('RefelectionFunction')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
