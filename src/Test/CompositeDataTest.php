<?php
namespace EyeOpen\Tests;

use EyeOpen\CompositeData;
use EyeOpen\ArrayObject;

/**
 * Testcase for the copy data.
 */
class CompositeDataTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CompositeData */
    private $data;

    /**
     * Creates a service for testing.
     */
    protected function setUp()
    {
        $this->data = new CompositeData();
    }

    /**
     * Only accepts Arrayable implementations
     *
     * @expectedException PHPUnit_Framework_Error
     * @test
     */
    public function shouldThrowErrorWhenNotPassedArrayable()
    {
        $this->data->addObject(new \stdClass());
    }

    /**
     * Should pass getter.
     *
     * @test
     */
    public function shouldCallGetter()
    {
        $object = $this->getMock('\EyeOpen\Arrayable', array('toArray', 'getFooBar'));

        $object->expects($this->once())
            ->method('getFooBar')
            ->will($this->returnValue('baz'));

        $this->data->addObject($object);

        $this->assertEquals('baz', $this->data->fooBar);
    }

    /**
     * Should pass getter.
     *
     * @test
     */
    public function shouldCallGetterWithMultipleObjects()
    {
        $class = '\EyeOpen\Arrayable';

        $objectA = $this->getMock($class, array('toArray'));
        $objectB = $this->getMock($class, array('toArray', 'getFooBar'));
        $objectC = $this->getMock($class, array('toArray'));

        $objectB->expects($this->once())
            ->method('getFooBar')
            ->will($this->returnValue('baz'));

        $this->data->addObject($objectA);
        $this->data->addObject($objectB);
        $this->data->addObject($objectC);


        $this->assertEquals('baz', $this->data->fooBar);
    }


    /**
     * Should get data from inner objects.
     *
     * @test
     */
    public function shouldGetInnerData()
    {
        $class = '\EyeOpen\Arrayable';

        $objectA = $this->getMock($class, array('toArray', 'getFooBar'));
        $objectB = $this->getMock($class, array('toArray', 'getBlah'));

        $objectC = new ArrayObject(
            array(
                'test1' => 'testVal1',
                'test2' => 'testVal2'
            )
        );

        $objectA->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('fooBar' => '')));

        $objectB->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('blah' => '')));

        $objectA->expects($this->once())
            ->method('getFooBar')
            ->will($this->returnValue('baz'));

        $objectB->expects($this->once())
            ->method('getBlah')
            ->will($this->returnValue('top'));

        $this->data->addObject($objectA);
        $this->data->addObject($objectB);
        $this->data->addObject($objectC);

        $this->assertEquals(
            array(
                'fooBar' => 'baz',
                'blah' => 'top',
                'test1' => 'testVal1',
                'test2' => 'testVal2'
            ), $this->data->toArray()
        );
    }

    /**
     * Test if first is returned when multiple objects contain the same key.
     *
     * @test
     */
    public function shouldPreferDataFromFirstObject()
    {

        $objectA = new ArrayObject(
            array(
                'test1' => 'testVal1',
                'test2' => 'testVal2'
            )
        );

        $objectB = new ArrayObject(
            array(
                'test1' => 'blaat',
                'test3' => 'testVal3'
            )
        );

        $this->data->addObject($objectA);
        $this->data->addObject($objectB);

        $this->assertEquals(
            array(
                'test1' => 'testVal1',
                'test2' => 'testVal2',
                'test3' => 'testVal3'
            ), $this->data->toArray()
        );

        $this->assertEquals('testVal1', $this->data->test1);
    }

    /**
     * Only accepts Arrayable implementations
     *
     * @expectedException PHPUnit_Framework_Error
     * @test
     */
    public function shouldThrowErrorWhenNotPassedArrayableToSet()
    {
        $this->data->objectA = new \stdClass();
    }

    /**
     * Convert to non flat array when use set method
     *
     * @test
     */
    public function shouldConvertToNonFlatArray()
    {
        // skip it
        $this->data->getValueFromObject = new ArrayObject(
            array(
                'value' => 'moo'
            )
        );

        $this->data->setObjectA(new ArrayObject(
            array(
                'value' => 'moo'
            )
        ));
        $this->data->objectB = new ArrayObject(
            array(
                'something' => 'boo'
            )
        );
        $this->data->objectC = new ArrayObject(
            array(
                'value' => 'heythere'
            )
        );

        $this->assertEquals(
            array(
                'objectA' => array(
                    'value' => 'moo'
                ),
                'objectB' => array(
                    'something' => 'boo'
                ),
                'objectC' => array(
                    'value' => 'heythere'
                )
            ), $this->data->toArray()
        );

    }
}