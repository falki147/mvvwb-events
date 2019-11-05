<p>
    <label for="<?=self::START_DATE_NAME?>"><?=esc_html__('Date', 'mvvwb-events')?></label>

    <input id="<?=self::START_DATE_NAME?>" type="text"
        data-name="<?=self::START_DATE_NAME?>"
        class="widefat mvvwb-events-datepicker">

    <input type="hidden" name="<?=self::START_DATE_NAME?>"
        value="<?=esc_attr($startdate)?>"
        data-timezone="<?=esc_attr(MVVWB\Events\EventHelper::getTimezone()->getName())?>">
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
        data-timezone="<?=esc_attr(MVVWB\Events\EventHelper::getTimezone()->getName())?>">
</p>

<p>
    <label for="<?=self::ADDITIONAL_TEXT_NAME?>"><?=esc_html__('Additional Text', 'mvvwb-events')?></label>

    <input id="<?=self::ADDITIONAL_TEXT_NAME?>"
        name="<?=self::ADDITIONAL_TEXT_NAME?>"
        type="text" class="widefat" value="<?=esc_attr($additional)?>">
</p>
