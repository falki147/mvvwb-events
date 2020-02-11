<?php

namespace MVVWB\Events;

class RegisterHelper {
    public static function register() {
        add_action('plugins_loaded', function () { self::setup(); });
        add_action('widgets_init', function () { self::widgetsInit(); });
        add_action('add_meta_boxes', function () { self::addMetaBoxes(); });
        add_action('save_post', function ($postID) { self::saveMetaBoxes($postID); });
    }

    private static function setup() {
        load_plugin_textdomain('mvvwb-events', false, MVVWB_EVENTS_TRANLATIONS);

        wp_register_style(
            'mvvwb-events',
            MVVWB_EVENTS_BASE . 'style.css', [],
            MVVWB_EVENTS_VERSION
        );

        wp_register_script(
            'mvvwb-events-admin',
            MVVWB_EVENTS_BASE . 'admin.js',
            [ 'wp-blocks', 'wp-element', 'wp-data', 'jquery' ],
            MVVWB_EVENTS_VERSION
        );

        wp_register_style(
            'mvvwb-events-admin',
            MVVWB_EVENTS_BASE . 'admin.css', [],
            MVVWB_EVENTS_VERSION
        );

        register_post_type('event', [
            'labels' => [
                'name'          => __('Events', 'mvvwb-events'),
                'singular_name' => __('Event', 'mvvwb-events'),
                'add_new'       => __('Add Event', 'mvvwb-events'),
                'edit_item'     => __('Edit Event', 'mvvwb-events')
            ],
            'public'            => false,
            'show_ui'           => true,
            'capability_type'   => 'post',
            'map_meta_cap'      => true,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
            'show_in_nav_menus' => false,
            'delete_with_user'  => false,
            'supports'          => [ 'title' ],
            'show_in_rest'      => false,
            'menu_icon'         => 'dashicons-calendar-alt'
        ]);

        register_block_type('mvvwb/events', [
            'editor_script' => 'mvvwb-events-admin',
            'render_callback' => function ($attributes, $content ) {
                wp_enqueue_style('mvvwb-events');

                $events = array_map(function ($event) {
                    return [
                        'event' => EventHelper::getStartDate($event),
                        'title' => $event->post_title,
                        'content' => EventHelper::getFullString($event),
                        'additionalText' => EventHelper::getAdditionalText($event)
                    ];
                }, EventHelper::getEvents());
            
                ob_start();
                include MVVWB_EVENTS_VIEWS . 'EventsBlockView.php';
                return ob_get_clean();
            }
        ]);
    }

    private static function widgetsInit() {
        register_widget(new EventsWidget);
    }

    private static function addMetaBoxes() {
        EventMetabox::addMetabox();
    }

    private static function saveMetaBoxes($postID) {
        EventMetabox::saveMetabox($postID, $_POST);
    }
}
