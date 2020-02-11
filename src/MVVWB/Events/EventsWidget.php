<?php

namespace MVVWB\Events;

class EventsWidget extends \WP_Widget {
    function __construct() {
        parent::__construct(
            'events-widget', __('Upcoming Events', 'mvvwb-events')
        );
    }

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

    public function form($instance) {
        echo '<p></p>';
    }

    public function upevent($newInstance, $oldInstance) {
        return [];
    }
}
