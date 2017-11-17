<?php 

use GinoPane\Template\YourClass;

/**
*  Corresponding class to test YourClass class
*
*  For each class in your library, there should be a corresponding unit test
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class YourClassTest extends PHPUnit\Framework\TestCase
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