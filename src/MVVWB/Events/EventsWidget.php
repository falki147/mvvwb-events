<?php
/**
 * Defines EventsWidget class
 */

namespace MVVWB\Events;

/**
 * Events widget which displays all the events
 */
class EventsWidget extends \WP_Widget {
    /**
     * Construct the widget with default values
     */
    function __construct() {
        parent::__construct(
            'events-widget', __('Upcoming Events', 'mvvwb-events')
        );
    }

    /**
     * Render the widget
     *
     * The HTML code is written to the output buffer.
     *
     * @param string[] $args display arguments
     * @param string[] $instance settings for the particular instance
     */
    public function widget($args, $instance) {
        wp_enqueue_style('mvvwb-events');

        $eventPosts = EventHelper::getEvents();
        $maxEvents = min(2, count($eventPosts));
        $events = [];

        for ($i = 0; $i < $maxEvents; ++$i)
            $events[] = [
                'date' => EventHelper::getStartDate($eventPosts[$i]),
                'title' => $eventPosts[$i]->post_title,
                'content' => EventHelper::getFullString($eventPosts[$i]),
                'additionalText' => EventHelper::getAdditionalText($eventPosts[$i])
            ];

        echo $args['before_widget'];

        if ($events)
            include MVVWB_EVENTS_VIEWS . 'EventsWidgetView.php';

        echo $args['after_widget'];
    }
}
