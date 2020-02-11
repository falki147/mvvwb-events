<?php

namespace MVVWB\Events;

/**
 * Handles the metabox functionality for the event data
 *
 * It creates the HTML for the metabox and handles the storing of the data with the help of the
 * EventHelper.
 *
 * This class is not intended to be intantiated
 */
class EventMetabox {
    /**
     * Name of the date start input field
     * @internal
     */
    const START_DATE_NAME = 'mvvwb_date_start';

    /**
     * Name of the date end input field
     * @internal
     */
    const END_DATE_NAME = 'mvvwb_date_end';

    /**
     * Name of the date format input field
     * @internal
     */
    const DATE_FORMAT_NAME = 'mvvwb_date_format';

    /**
     * Name of the additional text input field
     * @internal
     */
    const ADDITIONAL_TEXT_NAME = 'mvvwb_date_additional';

    /**
     * Adds the metabox to wordpress
     */
    public static function addMetabox() {
        add_meta_box(
            'event',
            __('Event', 'mvvwb-events'),
            function ($post) {
                wp_enqueue_style('mvvwb-events-admin');
                wp_enqueue_script('mvvwb-events-admin');

                $startdate = EventHelper::format(\DateTime::ISO8601 , EventHelper::getStartDate($post));
                $dateformat = EventHelper::unstripQuotationMarks(EventHelper::getDateFormat($post));
                $enddate = EventHelper::format(\DateTime::ISO8601 , EventHelper::getEndDate($post));
                $additional = EventHelper::getAdditionalText($post);

                include MVVWB_EVENTS_ADMIN_VIEWS . 'EventMetaboxView.php';
            },
            [ 'event' ]
        );
    }

    /**
     * Saves the data to the post if the fields were set
     *
     * @param int $postID id of the post
     * @param mixed[] $values values which were sent with the request e.g. $_POST
     */
    public static function saveMetabox($postID, $values) {
        $post = \get_post($postID);

        if (isset($values[self::START_DATE_NAME]))
            EventHelper::setStartDate($post, new \DateTime($values[self::START_DATE_NAME]));

        if (isset($values[self::DATE_FORMAT_NAME]))
            EventHelper::setDateFormat(
                $post, EventHelper::stripQuotationMarks(wp_unslash($values[self::DATE_FORMAT_NAME]))
            );

        if (isset($values[self::END_DATE_NAME]))
            EventHelper::setEndDate($post, new \DateTime($values[self::END_DATE_NAME]));

        if (isset($values[self::ADDITIONAL_TEXT_NAME]))
            EventHelper::setAdditionalText($post, wp_unslash($values[self::ADDITIONAL_TEXT_NAME]));
    }
}
