<?php

namespace App\Tests\Service;

use App\Service\CalculatorService;
use PHPUnit\Framework\TestCase;

class CalculatorServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testTva()
    {

        $calculator = new CalculatorService(0.2);
        $result = $calculator->tva(100);

        $calculator2 = new CalculatorService(0.1);
        $result2 = $calculator2->tva(100);

        // assertion
        // https://phpunit.readthedocs.io/en/9.5/assertions.html#assertequals
        $this->assertEquals(120, $result);
        $this->assertEquals(110, $result2);
    }

    public function testSquare()
    {
        $calculator = new CalculatorService();
        $result = $calculator->square(5);

        // assertion
        $this->assertEquals(25, $result);
        $this->assertNotEquals("vingt-cinq", $result);
        $this->assertEquals("25", $result); // conversion implicite par assertEquals
    }
}
