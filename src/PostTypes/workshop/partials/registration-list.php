<?php
global $wpdb;

$posts = get_posts(array(
    'post_type'         => 'workshop',
    'posts_per_page'    =>  -1,
    'orderby'           => 'meta_key',
    'meta_key'          => 'workshop_start',
    'order'             => 'ASC'
));


usort($posts, function ($a, $b) {

    $start_date_a = get_post_meta($a->ID, 'workshop_start', true);
    $start_date_b = get_post_meta($b->ID, 'workshop_start', true);

    if ($start_date_a->format('Y-m-d') > $start_date_b->format('Y-m-d')) {
        return 1;
    }

    if ($start_date_a->format('Y-m-d') < $start_date_b->format('Y-m-d')) {
        return -1;
    }

    return 0;
});

?>


<?php
// get registrations for selected workshop

if (isset($_GET["workshop_id"])) :
    $sql_registrations = "SELECT * FROM makerspace_calendar_workshop_registrations WHERE mse_cal_workshop_post_id = %d";
    $event_registrations = $wpdb->get_results($wpdb->prepare($sql_registrations, $_GET["workshop_id"]));
endif;

?>



<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h3>Anmeldungen für Workshops</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="row">

                <?php foreach ($posts as $post) : ?>
    
                    <?php $start_date = get_post_meta($post->ID, 'workshop_start', true) ?>

                    <?php if ( $start_date->format('Y-m-d') >= date( 'Y-m-d') ): ?>

                        <!-- <a href="/wp-admin/edit.php?post_type=workshop&page=ms_events_registrations&workshop_id=<?php echo $post->ID; ?>"> -->
                        <div class="col-12 card p-0 " onclick="window.location.href = '/wp-admin/edit.php?post_type=workshop&page=ms_events_registrations&workshop_id=<?php echo $post->ID; ?>'">
                            <div class="card-header d-flex">

                            <?php 
                                $max_regs = get_post_meta( $post->ID, 'workshop_option_free_seats', true);
                                $sql_registrations = "SELECT SUM(mse_cal_workshop_registration_count) as mse_cal_reg_count FROM makerspace_calendar_workshop_registrations WHERE mse_cal_workshop_post_id = %d";
                                $current_regs = $wpdb->get_var( $wpdb->prepare($sql_registrations, $post->ID) );
                            ?>


                                <span><?php echo get_the_title($post->ID) ?></span>
                                <span class="badge badge-primary ml-auto"><?php echo ($current_regs > 0)? $current_regs: 0; ?> / <?php echo ($max_regs > 0)? $max_regs: 0; ?></span>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo get_the_excerpt($post->ID) ?></p>
                            </div>
                        </div>
                        <!-- </a> -->
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="col">
            <div class="list-group">



                <?php if (isset($_GET["workshop_id"])) : ?>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>EMail</th>
                                <th>Vorname</th>
                                <th>Nachname</th>
                                <th>Anzahl</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($event_registrations as $reg) : ?>
                                <tr>
                                    <td><?php echo $reg->mse_cal_workshop_registration_id; ?></td>
                                    <td><?php echo $reg->mse_cal_workshop_registration_email; ?></td>
                                    <td><?php echo $reg->mse_cal_workshop_registration_firstname; ?></td>
                                    <td><?php echo $reg->mse_cal_workshop_registration_lastname; ?></td>
                                    <td><?php echo $reg->mse_cal_workshop_registration_count; ?></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>EMail</th>
                                <th>Vorname</th>
                                <th>Nachname</th>
                                <th>Anzahl</th>
                            </tr>
                        </tfoot>
                    </table>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>