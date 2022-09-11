<?php

require_once 'src/functions.inc.php';

use function App\getClassification;

class functionsTest extends \PHPUnit\Framework\TestCase
{


    /**
     * Create dependencies to mimic the callbacks of getClassification()
     */
    public function testCreateArray(): array
    {
        $input = array();

        $input = array(
            "Programming" => 64,
            "Cloud Computing" => 85,
            "Computing Foundations" => 64,
            "Databases" => 64,
            "Web Development" => 64,
            "Software Engineering" => 64,
            "Data Analysis" => 95,
            "User Experience" => 85,
        );

        $this->assertNotEmpty($input);

        return $input;
    }


    /**
     * @depends testCreateArray
     */
    public function testDoubleWeighted(array $input): array
    {

        $expected = "Programming2";
        $input = App\doubleWeighted($input);

        $this->assertArrayHasKey($expected, $input);

        return $input;
    }

    /**
     * @depends testDoubleWeighted
     */
    public function testGetAverage(array $input): float
    {

        $expected = 72.11111111111111;
        $average = App\getAverage($input);

        $this->assertEquals($expected, $average);

        return $average;
    }

    /**
     * @depends testDoubleWeighted
     */
    public function testGetDisAverage(array $input): float
    {

        $expected = 88.33333333333333;
        $disAverage = App\getDisAverage($input);

        $this->assertEquals($expected, $disAverage);

        return $disAverage;
    }

    /**
     * @depends testDoubleWeighted
     */
    public function testGetOtherAverage(array $input): float
    {

        $expected = 64.0;
        $otherAverage = App\getOtherAverage($input);

        $this->assertEquals($expected, $otherAverage);

        return $otherAverage;
    }

    /**
     * @depends testGetAverage
     * @depends testGetDisAverage
     * @depends testGetOtherAverage
     */
    public function testClassify(float $average, float $disAverage, float $otherAverage)
    {

        $expected = 'Commendation';
        $result = App\classify($average, $disAverage, $otherAverage);
        $this->assertEquals($expected, $result);
    }
}
