<?php

namespace Heffe\SUFAPIBundle\Services;

class DateService
{
    /**
     * @param $offset integer Number of seconds to add to go from Local to UTC
     * @param $date \DateTime Date to convert
     * @return \DateTime
     */
    public static function convertDateToLocal($date, $offset)
    {
        if($date == null)
        {
            return null;
        }

        $offset = -1 * $offset;

        $date->setTimezone(new \DateTimeZone('UTC'));

        if($offset >= 0)
        {
            $date->add(new \DateInterval('PT' . $offset . 'S'));
        }
        else
        {
            $offset = -1 * $offset;
            $date->sub(new \DateInterval('PT' . $offset . 'S'));
        }


        return $date;
    }

    public static function convertUserNotesDatesToLocal($userNotes, $offset)
    {
        if($userNotes != null)
        {
            foreach($userNotes as $note)
            {
                $note->setDateCreated( DateService::convertDateToLocal($note->getDateCreated(), $offset) );
                $note->setDateUpdated( DateService::convertDateToLocal($note->getDateUpdated(), $offset));
            }
        }

        return $userNotes;
    }
}