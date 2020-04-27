<?php
class UNL_MediaHub_DurationHelper
{
    protected $details = [
        'total_milliseconds' => 0,
        'total_seconds' => 0,
        'milliseconds' => 0,
        'hours' => 0,
        'minutes' => 0,
        'seconds' => 0,
        'string' => '00:00:00'
    ];

    /**
     * UNL_MediaHub_DurationHelper constructor.
     * @param $total_milliseconds
     */
    function __construct($total_milliseconds) {
        $duration = [];
        //Duration in totals
        $duration['total_milliseconds'] = $total_milliseconds;
        $duration['total_seconds'] = floor($total_milliseconds / 1000);
        
        //duration in units
        $duration['milliseconds'] = $duration['total_milliseconds'] % 1000;
        $duration['hours'] =  floor($duration['total_seconds'] / (60 * 60));
        $duration['minutes'] = floor(($duration['total_seconds'] / 60) % 60);
        $duration['seconds'] = floor((($duration['total_seconds'] % 60) * 100) / 100);
        
        //string
        $duration['string'] = str_pad($duration['hours'], 2, '0', STR_PAD_LEFT);
        $duration['string'] .= ':' . str_pad($duration['minutes'], 2, '0', STR_PAD_LEFT);
        $duration['string'] .= ':' . str_pad($duration['seconds'], 2, '0', STR_PAD_LEFT);
        
        $this->details = $duration;
    }
    
    public function getTotalMilliseconds()
    {
        return $this->details['total_milliseconds'];
    }
    
    public function getTotalSeconds()
    {
        return $this->details['total_seconds'];
    }
    
    public function getMilliseconds()
    {
        return $this->details['milliseconds'];
    }
    
    public function getSeconds()
    {
        return $this->details['seconds'];
    }
    
    public function getMinutes()
    {
        return $this->details['minutes'];
    }
    
    public function getHours()
    {
        return $this->details['hours'];
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->details['string'];
    }
    
    public function __toString()
    {
        return $this->getString();
    }
}
