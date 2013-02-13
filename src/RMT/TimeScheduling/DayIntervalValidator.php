<?php

namespace RMT\TimeScheduling;

use Symfony\Component\Validator\ExecutionContext;
use RMT\TimeScheduling\Model\DayInterval;
use RMT\TimeScheduling\Model\DayIntervalQuery;

class DayIntervalValidator
{
    public static function isValidInterval(DayInterval $day_interval, ExecutionContext $context)
    {
        if($day_interval->getStartHour() >= $day_interval->getEndHour())
        {
       	    $context->addViolationAtSubPath('start_hour', 'The start must be earlier than the end hour.', array(), null);
        }
    }
    public static function isUniqueDay(DayInterval $day_interval, ExecutionContext $context)
    {
    	$count = DayIntervalQuery::create()
    		->filterByUser($day_interval->getUser())
    		->filterByDay($day_interval->getDay())
    		->count();

        if($count > 0)
        {
       	    $context->addViolationAtSubPath('day', 'You already defined hours for this day.', array(), null);
        }
    }
}