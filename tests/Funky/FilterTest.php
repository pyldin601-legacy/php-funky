<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.03.2016
 * Time: 15:43
 */

namespace Funky;


use Funky\Option\Filter;

class FilterTest extends Misc\TestCase
{
    public function testNumberFilter()
    {
        $gt = Filter\NumberFilter::gt(10);
        $lt = Filter\NumberFilter::lt(10);
        $ge = Filter\NumberFilter::ge(5);
        $le = Filter\NumberFilter::le(5);
        $range = Filter\NumberFilter::range(5, 10);

        $this->assertTrue($gt(15));
        $this->assertFalse($gt(5));

        $this->assertTrue($lt(5));
        $this->assertFalse($lt(15));

        $this->assertTrue($ge(5));
        $this->assertFalse($ge(0));

        $this->assertTrue($le(5));
        $this->assertFalse($ge(0));

        $this->assertTrue($range(7));
        $this->assertFalse($range(0));
        $this->assertFalse($range(20));
    }

    public function testStringFilter()
    {
        $lengthEq = Filter\StringFilter::lengthEq(5);
        $this->assertTrue($lengthEq("Hello"));
        $this->assertFalse($lengthEq("foo"));

        $lengthGt = Filter\StringFilter::lengthGt(2);
        $this->assertTrue($lengthGt("foo"));
        $this->assertFalse($lengthGt("f"));

        $lengthLt = Filter\StringFilter::lengthLt(2);
        $this->assertTrue($lengthLt("f"));
        $this->assertFalse($lengthLt("foo"));

        $lengthGe = Filter\StringFilter::lengthGe(2);
        $this->assertTrue($lengthGe("hi"));
        $this->assertTrue($lengthGe("foo"));
        $this->assertFalse($lengthGe("f"));

        $lengthLe = Filter\StringFilter::lengthLe(2);
        $this->assertTrue($lengthLe("hi"));
        $this->assertTrue($lengthLe("f"));
        $this->assertFalse($lengthLe("foo"));

        $range = Filter\StringFilter::range(8, 16);
        $this->assertTrue($range("hello, world!"));
        $this->assertFalse($range("short"));
        $this->assertFalse($range("loooooooooooooong"));

        $foundIn = Filter\StringFilter::foundIn(["foo", "bar"]);
        $this->assertTrue($foundIn("foo"));
        $this->assertTrue($foundIn("bar"));
        $this->assertFalse($foundIn("hello"));

        $matcher = Filter\StringFilter::match("[0-9]+");
        $this->assertTrue($matcher("19"));
        $this->assertTrue($matcher("0"));
        $this->assertTrue($matcher("hello11"));
        $this->assertFalse($matcher("hello"));
    }
}