<?php
/**
 * File which is used to render the events metabox HTML
 *
 * Following variables are passed to it:
 * - self::START_DATE_NAME: Name of the start date HTML input element
 * - self::DATE_FORMAT_NAME: Name of the date format HTML input element
 * - self::END_DATE_NAME: Name of the end date HTML input element
 * - self::ADDITIONAL_TEXT_NAME: Name of the additional text HTML input element
 * - $startdate: A string containing the start date of the event
 * - $dateformat: A string containing the date format of the event
 * - $enddate: A string containing the end date of the event
 * - $additional: A string containing the additional text of the event
 */

namespace MVVWB\Events\Views\Admin;

use MVVWB\Events\EventHelper;

?><p>
    <label for="<?=self::START_DATE_NAME?>"><?=esc_html__('Date', 'mvvwb-events')?></label>

    <input id="<?=self::START_DATE_NAME?>" type="text"
        data-name="<?=self::START_DATE_NAME?>"
        class="widefat mvvwb-events-datepicker">

    <input type="hidden" name="<?=self::START_DATE_NAME?>"
        value="<?=esc_attr($startdate)?>"
        data-timezone="<?=esc_attr(EventHelper::getTimezone()->getName())?>">
</p>

<p>
    <label for="<?=self::DATE_FORMAT_NAME?>"><?=esc_html__('Dateformat', 'mvvwb-events')?></label>

    <input id="<?=self::DATE_FORMAT_NAME?>"
        name="<?=self::DATE_FORMAT_NAME?>"
        type="text" class="widefat" value="<?=esc_attr($dateformat)?>">
</p>

<p>
    <label for="<?=self::END_DATE_NAME?>"><?=esc_html__('Enddate', 'mvvwb-events')?></label>

    <input id="<?=self::END_DATE_NAME?>" type="text"
        data-name="<?=self::END_DATE_NAME?>"
        class="widefat mvvwb-events-datepicker">

    <input type="hidden" name="<?=self::END_DATE_NAME?>"
        value="<?=esc_attr($enddate)?>"
        data-timezone="<?=esc_attr(EventHelper::getTimezone()->getName())?>">
</p>

<p>
    <label for="<?=self::ADDITIONAL_TEXT_NAME?>"><?=esc_html__('Additional Text', 'mvvwb-events')?></label>

    <input id="<?=self::ADDITIONAL_TEXT_NAME?>"
        name="<?=self::ADDITIONAL_TEXT_NAME?>"
        type="text" class="widefat" value="<?=esc_attr($additional)?>">
</p>
