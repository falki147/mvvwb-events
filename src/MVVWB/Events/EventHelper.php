<?php

namespace MVVWB\Events;

class EventHelper {
    const KEY_STARTDATE = 'mvvwb_date_start';
    const KEY_ENDDATE = 'mvvwb_date_end';
    const KEY_DATEFORMAT = 'mvvwb_date_format';
    const KEY_ADDITIONAL_TEXT = 'mvvwb_additional';

    public static function format($format, $date) {
        if ($date === null)
            return '';

        // Create local timezone
        $timezoneStr = get_option('timezone_string') ?: 'UTC';
        $timezone = new \DateTimeZone($timezoneStr);

        // Convert to local timezone
        $date = clone $date;
        $date->setTimezone(self::getTimezone());
        $dateStr = $date->format('Y-m-d H:i:s');

        // Pretend the local date is UTC
        $utcDate = new \DateTime($dateStr, new \DateTimeZone('UTC'));
        return date_i18n($format, $utcDate->getTimestamp(), true);
    }

    public static function getTimezone() {
        $timezoneStr = get_option('timezone_string') ?: 'UTC';
        return new \DateTimeZone($timezoneStr);
    }

    public static function getEvents() {
        $events = get_posts([ 'post_type' => 'event', 'numberposts' => -1 ]);
        $now = new \DateTime();

        foreach ($events as $i => $event) {
            $enddate = self::getEndDate($event);

            if ($enddate < $now) {
                wp_delete_post($event->ID);
                unset($events[$i]);
            }
        }

        $eventCache = [];

        foreach ($events as $event)
            $eventCache[$event->ID] = self::getStartDate($event);

        usort($events, function ($a, $b) use ($eventCache) {
            $a = $eventCache[$a->ID];
            $b = $eventCache[$b->ID];

            if ($a == $b)
                return 0;

            return ($a < $b) ? -1 : 1;
        });

        return $events;
    }

    public static function getStartDate($post) {
        $event = get_post_meta($post->ID, self::KEY_STARTDATE, true);
        return new \DateTime($event ?: '+1 hour');
    }

    public static function setStartDate($post, $event) {
        update_post_meta(
            $post->ID, self::KEY_STARTDATE,
            $event !== null ? $event->format(\DateTime::ISO8601) : ''
        );
    }

    public static function getEndDate($post) {
        $event = get_post_meta($post->ID, self::KEY_ENDDATE, true);
        return new \DateTime($event ?: '+2 hour');
    }

    public static function setEndDate($post, $event) {
        update_post_meta(
            $post->ID, self::KEY_ENDDATE,
            $event !== null ? $event->format(\DateTime::ISO8601) : ''
        );
    }

    public static function getDateFormat($post) {
        $format = get_post_meta($post->ID, self::KEY_DATEFORMAT, true);
        return $format ?: 'l, j. F Y H:i';
    }

    public static function setDateFormat($post, $value) {
        update_post_meta($post->ID, self::KEY_DATEFORMAT, wp_slash($value));
    }

    public static function getAdditionalText($post) {
        return get_post_meta($post->ID, self::KEY_ADDITIONAL_TEXT, true);
    }

    public static function setAdditionalText($post, $value) {
        update_post_meta($post->ID, self::KEY_ADDITIONAL_TEXT, wp_slash($value));
    }

    public static function getFullString($post) {
        $format = self::getDateFormat($post);
        return self::format($format, self::getStartDate($post));
    }

    public static function stripQuotationMarks($str) {
        return preg_replace_callback('/"([^"]*)"/', function ($matches) {
            if (!$matches[1])
                return '"';

            $output = '';
            $length = strlen($matches[1]);

            for ($i = 0; $i < $length; ++$i)
                $output .= '\\' . $matches[1][$i];

            return $output;
        }, $str);
    }

    public static function unstripQuotationMarks($str) {
        return preg_replace_callback('/(\\\\.)+/', function ($matches) {
            $output = '';
            $length = strlen($matches[0]);

            for ($i = 1; $i < $length; $i += 2)
                $output .= $matches[0][$i];

            return '"' . $output . '"';
        }, str_replace('"', '""', $str));
    }
}
