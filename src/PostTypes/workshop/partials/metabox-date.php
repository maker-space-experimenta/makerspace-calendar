<?php

global $post;

// Nonce field to validate form request came from current site
wp_nonce_field( basename( __FILE__ ), 'metabox_workshop_date' );

?>


<div class="fluid-container">

    <!-- Betriebsanweisung -->
    <div class="row">
        <?php $workshop_start = get_post_meta($post->ID, 'workshop_start', true); ?>
        <?php $workshop_end = get_post_meta($post->ID, 'workshop_end', true); ?>

        <div class="col-12">
            <label for="workshop_start_date">Beginn</label>
            <div class="input-group">
                <input type="date"
                       class="form-control"
                       id="workshop_start_date"
                       name="workshop_start_date"
                       value="<?php if($workshop_start) { echo  $workshop_start->format('Y-m-d'); } ?>">
                <div class="input-group-append">
                    <label for="workshop_start_date" class="input-group-text"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../../../assets/icons/calendar.svg'  ?>" /></label>
                </div>
            </div>
        </div>

        <div class="col-12">
            <label for="workshop_start_time"></label>
            <div class="input-group">
                <input type="time"
                       class="form-control"
                       id="workshop_start_time"
                       name="workshop_start_time"
                       value="<?php if($workshop_start) { echo  $workshop_start->format('H:i'); } ?>">
                <div class="input-group-append">
                    <label for="workshop_start_time" class="input-group-text"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../../../assets/icons/clock.svg'  ?>" /></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">


        <div class="col-12">
            <label for="workshop_end_date">Ende</label>
            <div class="input-group">
                <input type="date"
                       class="form-control"
                       id="workshop_end_date"
                       name="workshop_end_date"
                       value="<?php if($workshop_end) { echo  $workshop_end->format('Y-m-d'); } ?>">
                <div class="input-group-append">
                    <label for="workshop_end_date" class="input-group-text"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../../../assets/icons/calendar.svg'  ?>" /></label>
                </div>
            </div>
        </div>

        <div class="col-12">
            <label for="workshop_end_time"></label>
            <div class="input-group">
                <input type="time"
                       class="form-control"
                       id="workshop_end_time"
                       name="workshop_end_time"
                       value="<?php if($workshop_end) { echo  $workshop_end->format('H:i'); } ?>">
                <div class="input-group-append">
                    <label for="workshop_end_time" class="input-group-text"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../../../assets/icons/clock.svg'  ?>" /></label>
                </div>
            </div>
        </div>

    </div>
</div>