<?php

namespace MVVWB\Events;

class EventMetabox {
    const START_DATE_NAME = 'mvvwb_date_start';
    const END_DATE_NAME = 'mvvwb_date_end';
    const DATE_FORMAT_NAME = 'mvvwb_date_format';
    const ADDITIONAL_TEXT_NAME = 'mvvwb_date_additional';

    public static function addMetabox() {
        add_meta_box(
            'event',
            __('Event', 'mvvwb-events'),
            function ($post) {
                $startdate = EventHelper::format(\DateTime::ISO8601 , EventHelper::getStartDate($post));
                $dateformat = EventHelper::unstripQuotationMarks(EventHelper::getDateFormat($post));
                $enddate = EventHelper::format(\DateTime::ISO8601 , EventHelper::getEndDate($post));
                $additional = EventHelper::getAdditionalText($post);

                include MVVWB_EVENTS_ADMIN_VIEWS . 'EventMetaboxView.php';
            },
            [ 'event' ]
        );
    }

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
