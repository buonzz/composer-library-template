<?php 

namespace GinoPane\Template;

use PHPUnit\Framework\TestCase;

/**
*  Corresponding class to test YourClass class
*
*  For each class in your library, there should be a corresponding unit test
*
*  @author yourname
*/
class YourClassTest extends TestCase
{
    /**
     * Just check if the YourClass has no syntax errors
     */
    public function testIsThereAnySyntaxError()
    {
        $object = new YourClass();

        $this->assertTrue(is_object($object));
    }

    /**
     * Test the only existing method of the class
     *
     * @dataProvider getNamesAndGreetings
     *
     * @param $name
     * @param $expected
     */
    public function testSayHello($name, $expected)
    {
        $object = new YourClass();

        $this->assertEquals($expected, $object->sayHello($name));
    }

    /**
     * Data for sayHello
     *
     * @return array
     */
    public function getNamesAndGreetings(): array
    {
        return [
            ['world', "Hello World!"],
            ['World', "Hello World!"]
        ];
    }
}