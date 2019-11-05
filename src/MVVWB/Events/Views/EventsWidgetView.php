<article class="events-widget" aria-label="<?=esc_attr__('Upcoming Events', 'mvvwb-events')?>">
    <?php foreach ($events as $event): ?>
        <article class="event" aria-label="<?=esc_attr__('Event', 'mvvwb-events')?>">
            <div class="event-content">
                <time class="event-date"
                      datetime="<?=esc_attr(MVVWB\Events\EventHelper::format('Y-m-d', $event['date']))?>">
                    <?=MVVWB\Events\EventHelper::format('j<\\sp\\a\\n>M</\\sp\\a\\n>', $event['date'])?>
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
</div>
