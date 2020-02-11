<?php
/**
 * Defines EventHelper class
 */

namespace MVVWB\Events;

/**
 * Helps with loading and saving the event data
 *
 * This class is not intended to be intantiated
 */
class EventHelper {
    /**
     * Meta name of the date start field
     * @internal
     */
    const KEY_STARTDATE = 'mvvwb_date_start';

    /**
     * Meta name of the date end field
     * @internal
     */
    const KEY_ENDDATE = 'mvvwb_date_end';

    /**
     * Meta name of the date format field
     * @internal
     */
    const KEY_DATEFORMAT = 'mvvwb_date_format';

    /**
     * Meta name of the additional text field
     * @internal
     */
    const KEY_ADDITIONAL_TEXT = 'mvvwb_additional';

    /**
     * Formats a date with the given format in the current locale and timezone
     *
     * @param string $format the PHP date format
     * @param \DateTime $date the date object
     * @return string the formated 
     */
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

    /**
     * Gets the current timezone
     *
     * @return \DateTimeZone the current timezon set by wordpress (defaults to UTC)
     */
    public static function getTimezone() {
        $timezoneStr = get_option('timezone_string') ?: 'UTC';
        return new \DateTimeZone($timezoneStr);
    }

    /**
     * Retrieves all the events sorted by start date
     *
     * @return \WP_Post[] the events as posts
     */
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

        // Cache start dates
        $eventCache = [];

        foreach ($events as $event)
            $eventCache[$event->ID] = self::getStartDate($event);

        // Sort by start dates
        usort($events, function ($a, $b) use ($eventCache) {
            $a = $eventCache[$a->ID];
            $b = $eventCache[$b->ID];

            if ($a == $b)
                return 0;

            return ($a < $b) ? -1 : 1;
        });

        return $events;
    }

    /**
     * Retrieves the start date from a post object
     *
     * @param \WP_Post $post the post where to get the data from
     * @return \DateTime the date (defaults to now + 1 hour)
     */
    public static function getStartDate($post) {
        $event = get_post_meta($post->ID, self::KEY_STARTDATE, true);
        return new \DateTime($event ?: '+1 hour');
    }

    /**
     * Sets the start date of the given post
     *
     * @param \WP_Post $post the post where the data should be set
     * @param \DateTime|null $event the date or null to reset it
     */
    public static function setStartDate($post, $event) {
        update_post_meta(
            $post->ID, self::KEY_STARTDATE,
            $event !== null ? $event->format(\DateTime::ISO8601) : ''
        );
    }

    /**
     * Retrieves the end date from a post object
     *
     * @param \WP_Post $post the post where to get the data from
     * @return \DateTime the date (defaults to now + 2 hours)
     */
    public static function getEndDate($post) {
        $event = get_post_meta($post->ID, self::KEY_ENDDATE, true);
        return new \DateTime($event ?: '+2 hour');
    }

    /**
     * Sets the end date of the given post
     *
     * @param \WP_Post $post the post where the data should be set
     * @param \DateTime|null $event the date or null to reset it
     */
    public static function setEndDate($post, $event) {
        update_post_meta(
            $post->ID, self::KEY_ENDDATE,
            $event !== null ? $event->format(\DateTime::ISO8601) : ''
        );
    }

    /**
     * Retrieves the date format from a post object
     *
     * @param \WP_Post $post the post where to get the data from
     * @return string the format (defaults to l, j. F Y H:i)
     */
    public static function getDateFormat($post) {
        $format = get_post_meta($post->ID, self::KEY_DATEFORMAT, true);
        return $format ?: 'l, j. F Y H:i';
    }

    /**
     * Sets the date format of the given post
     *
     * @param \WP_Post $post the post where the data should be set
     * @param string $value the PHP format
     */
    public static function setDateFormat($post, $value) {
        update_post_meta($post->ID, self::KEY_DATEFORMAT, wp_slash($value));
    }

    /**
     * Retrieves the additional text from a post object
     *
     * @param \WP_Post $post the post where to get the data from
     * @return string the text (defaults to an empty string)
     */
    public static function getAdditionalText($post) {
        return get_post_meta($post->ID, self::KEY_ADDITIONAL_TEXT, true);
    }

    /**
     * Sets the additional text of the given post
     *
     * @param \WP_Post $post the post where the data should be set
     * @param string $value the text
     */
    public static function setAdditionalText($post, $value) {
        update_post_meta($post->ID, self::KEY_ADDITIONAL_TEXT, wp_slash($value));
    }

    /**
     * Gets the formated start date form a post
     *
     * @param \WP_Post $post the post object
     * @return string the text
     */
    public static function getFullString($post) {
        $format = self::getDateFormat($post);
        return self::format($format, self::getStartDate($post));
    }

    /**
     * Converts enquoted strings from to PHP date format strings
     *
     * e.g. '"Test" H:i' becomes '\\T\\e\\s\\t H:i', '""' becomes '"'
     *
     * @param string $str the enquoted string
     * @return string the unqoted string
     */
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

    /**
     * Converts escaped characters in PHP date format strings to enquoted strings
     *
     * e.g. '\\T\\e\\s\\t H:i' becomes '"Test" H:i', '"' becomes '""'
     *
     * @param string $str the unqoted string
     * @return string the enquoted string
     */
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
