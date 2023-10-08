<?php
/**
 * File which is used to render the events widget HTML
 *
 * Following variables are passed to it:
 * - $events: An array of the events containing date, title, content and additional text
 */

namespace MVVWB\Events\Views;

use MVVWB\Events\EventHelper;

?><article class="events-widget" aria-label="<?=esc_attr__('Upcoming Events', 'mvvwb-events')?>">
    <?php foreach ($events as $event): ?>
        <article class="event" aria-label="<?=esc_attr__('Event', 'mvvwb-events')?>">
            <div class="event-content">
                <time class="event-date"
                      datetime="<?=esc_attr(EventHelper::format('Y-m-d', $event['date']))?>">
                    <?=EventHelper::format('j<\\s\\p\\a\\n>M</\\s\\p\\a\\n>', $event['date'])?>
                </time>
                <h2 class="event-title"><?=$event['title']?></h2>
                <div class="events-data">
                    <div><?=$event['content']?></div>
                    <?php if ($event['additionalText']):?>
                        <div><?=$event['additionalText']?></div>
                    <?php endif ?>
                </div>
            </div>
        </article>
    <?php endforeach ?>
</article>
