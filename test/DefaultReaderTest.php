<?php
/**
 * Annotation - Test Suite
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\AnnotationTest;

use Chrisguitarguy\Annotation\DefaultReader;

class DefaultReaderTest extends \PHPUnit_Framework_TestCase
{
    private $reader, $parser, $collection;

    public function testSetGetCollection()
    {
        $c = $this->getMock('Chrisguitarguy\\Annotation\\AnnotationCollectionInterface');

        $this->assertSame($this->reader, $this->reader->setCollection($c));
        $this->assertSame($c, $this->reader->getCollection());
    }

    public function testSetGetParser()
    {
        $p = $this->getMock('Chrisguitarguy\\Annotation\\ParserInterface');

        $this->assertSame($this->reader, $this->reader->setParser($p));
        $this->assertSame($p, $this->reader->getParser());
    }

    /** Bad classes/functions/etc *********************************************/

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadPropertyWithInvalidProperty()
    {
        $this->reader->readProperty('Nope', 'not_a_property');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadPropertiesWithInvalidClass()
    {
        $this->reader->readProperties('Nope');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadMethodWithInvalidMethod()
    {
        $this->reader->readMethod('Nope', 'notAMethod');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadMethodsWithInvalidClass()
    {
        $this->reader->readMethods('NotAClass');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadClassWithInvalidClass()
    {
        $this->reader->readClass('NotAClass');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReadFunctionWithInvalidFunction()
    {
        $this->reader->readFunction('not_a_function');
    }

    /** Successful Reflection creation ****************************************/

    public function testReadProperty()
    {
        $this->setupSuccess();

        $res = $this->reader->readProperty('CggReaderTestClass', 'here');

        $this->assertCount(1, $res);
    }

    public function testReadProperties()
    {
        $this->setupSuccess();

        $res = $this->reader->readProperties('CggReaderTestClass');

        $this->assertCount(1, $res);
        $this->assertArrayHasKey('here', $res);
        $this->assertCount(1, $res['here']);
    }

    public function testReadMethod()
    {
        $this->setupSuccess();

        $res = $this->reader->readMethod('CggReaderTestClass', 'doStuff');

        $this->assertCount(1, $res);
    }

    public function testReadMethods()
    {
        $this->setupSuccess();

        $res = $this->reader->readMethods('CggReaderTestClass');

        $this->assertCount(1, $res);
        $this->assertArrayHasKey('doStuff', $res);
        $this->assertCount(1, $res['doStuff']);
    }

    public function testReadClass()
    {
        $this->setupSuccess();

        $res = $this->reader->readClass('CggReaderTestClass');

        $this->assertCount(1, $res);
    }

    public function testReadFunction()
    {
        $this->setupSuccess();

        $res = $this->reader->readFunction('cgg_reader_test_func');

        $this->assertCount(1, $res);
    }

    protected function setupSuccess()
    {
        $this->parser->expects($this->once())
            ->method('parse')
            ->will($this->returnValue(array(
                array('annot', array('one' => 'two')),
            )));

        $this->collection->expects($this->once())
            ->method('create')
            ->with(
                'annot',
                $this->arrayHasKey('one')
            )
            ->will($this->returnValue(new \stdClass()));
    }

    protected function setUp()
    {
        $this->parser = $this->getMock('Chrisguitarguy\\Annotation\\ParserInterface');
        $this->collection = $this->getMock('Chrisguitarguy\\Annotation\\AnnotationCollectionInterface');
        $this->reader = new DefaultReader($this->collection, $this->parser);
    }
}
