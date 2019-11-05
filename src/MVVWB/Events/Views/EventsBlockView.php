<div class="events" role="table" aria-label="<?=esc_attr__('Events', 'mvvwb-events')?>">
    <?php if (!$events): ?>
        <?=esc_html__('There are no up-to-event events.', 'mvvwb-events')?>
    <?php endif ?>

    <?php foreach ($events as $event): ?>
        <div class="events-row" role="row">
            <div class="events-cell title" role="cell"
                 aria-label="<?=esc_attr__('Title', 'mvvwb-events')?>">
                <?=$event['title']?>
            </div>
            <div class="events-cell" role="cell"
                 aria-label="<?=esc_attr__('Date', 'mvvwb-events')?>">
                <?=$event['content']?>
            </div>
            <div class="events-cell" role="cell"
                 aria-label="<?=esc_attr__('Additional Information', 'mvvwb-events')?>">
                <?=$event['additionalText']?>
            </div>
        </div>
    <?php endforeach ?>
</div>
