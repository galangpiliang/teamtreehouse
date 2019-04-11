<?php

class firstTest extends PHPUnit\Framework\TestCase{
    
    function firstAssertion(){
        $this->assertTrue(false);
    }

    function testSecondAssertion(){
        $this->assertTrue(false);
    }
    
    /** @test */
    function thirdAssertion(){
        $this->assertTrue(true);
    }

}