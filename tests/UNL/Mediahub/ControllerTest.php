<?php

class UNL_MediaHub_ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function addURLParams()
    {
        $base_url = 'http://example.org';
        
        $this->assertEquals(
            $base_url . '?test=1&test2=2',
            UNL_MediaHub_Controller::addURLParams($base_url, array('test'=>1, 'test2'=>'2')),
            'test adding params'
        );
        
        $this->assertEquals(
            $base_url,
            UNL_MediaHub_Controller::addURLParams($base_url),
            'if no params are sent, the url should not be altered'
        );
        
        $this->assertEquals(
            $base_url,
            UNL_MediaHub_Controller::addURLParams($base_url, array('format'=>'html')),
            'the html format should not be included'
        );
        
        $this->assertEquals(
            $base_url.'?format=json',
            UNL_MediaHub_Controller::addURLParams($base_url, array('format'=>'json')),
            'other formats should be included'
        );
        
        $this->assertEquals(
            $base_url,
            UNL_MediaHub_Controller::addURLParams($base_url, array('driver'=>'driver')),
            'the driver should not be included'
        
        );
        $this->assertEquals(
            $base_url,
            UNL_MediaHub_Controller::addURLParams($base_url, array('model'=>'model')),
            'the model should not be included'
        
        );
        $this->assertEquals(
            $base_url,
            UNL_MediaHub_Controller::addURLParams($base_url, array('filter'=>'filter')),
            'the filter should not be included'
        );
    }
}