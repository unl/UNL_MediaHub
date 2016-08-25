<?php

class UNL_MediaHub_DurationHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testDurationHelper()
    {
        $duration = new UNL_MediaHub_DurationHelper(295222);
        
        $this->assertEquals('00:04:55', $duration->getString());
        $this->assertEquals(222, $duration->getMilliseconds());

        $duration = new UNL_MediaHub_DurationHelper(0);
        $this->assertEquals('00:00:00', $duration->getString());

        $duration = new UNL_MediaHub_DurationHelper(29522222);
        $this->assertEquals('08:12:02', $duration->getString());
    }
}