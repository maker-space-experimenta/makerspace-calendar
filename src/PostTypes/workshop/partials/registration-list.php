<?php
global $wpdb;

function getUrl ($wsId, $filter) {
    $base_url = "/wp-admin/edit.php";
    $url_query = "?";

    $url_query .= "post_type=workshop&";
    $url_query .= "page=ms_events_registrations&";
    
    if ($wsId != null) {
        $url_query .= "workshop_id=" . $wsId . "&";
    }

    if ($filter != null) {
        $url_query .= "workshop_filter=" . $filter . "&";
    }

    return $base_url . $url_query;
}


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


<div class="container-fluid p-0">
    <div class="row">
        <div class="col">

            <h1 class="title pb-3">Aktuelle Workshops</h1>

            <div class="ms-workshops-filter pb-2">
                <a type="button" class="btn btn-link btn-sm" href="<?php echo getUrl( $_GET["workshop_id"], "upcommung" ); ?>" >Anstehende</a>
                <a type="button" class="btn btn-link btn-sm" href="<?php echo getUrl( $_GET["workshop_id"], "past" ); ?>">Vergangene</a>
                <a type="button" class="btn btn-link btn-sm" href="<?php echo getUrl( $_GET["workshop_id"], "draft" ); ?>">Entwürfe</a>
            </div>

            <table id="table-workshops" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Start</th>
                        <th>Ende</th>
                        <th>Erstellt am</th>
                        <th>Anmeldungen</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($posts as $workshop) :
                        $start_date = get_post_meta($workshop->ID, 'workshop_start', true);

                        if ($start_date->format('Y-m-d') >= date('Y-m-d')) :

                            $max_regs = get_post_meta($workshop->ID, 'workshop_option_free_seats', true);
                            $sql_registrations = "SELECT SUM(mse_cal_workshop_registration_count) as mse_cal_reg_count FROM makerspace_calendar_workshop_registrations WHERE mse_cal_workshop_post_id = %d";
                            $current_regs = $wpdb->get_var($wpdb->prepare($sql_registrations, $workshop->ID));
                            ?>

                            <tr style="cursor: pointer; " onclick="window.location.href = '<?php echo getUrl( $workshop->ID, null ); ?>'">
                                <td><?php echo $workshop->ID; ?></td>
                                <td><?php echo get_the_title($workshop->ID) ?></td>
                                <td><?php echo $start_date->format('Y-m-d'); ?></td>
                                <td></td>
                                <td></td>
                                <td><?php echo ($current_regs > 0) ? $current_regs : 0; ?> / <?php echo ($max_regs > 0) ? $max_regs : 0; ?></td>
                            </tr>

                        <?php endif; ?>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>


        <?php
        if (isset($_GET["workshop_id"])) :

            $sql_registrations = "SELECT * FROM makerspace_calendar_workshop_registrations WHERE mse_cal_workshop_post_id = %d";
            $event_registrations = $wpdb->get_results($wpdb->prepare($sql_registrations, $_GET["workshop_id"]));
            $ws_title = get_the_title($_GET["workshop_id"]);

            $registrations_emails = "";
            foreach ($event_registrations as $reg) {
                $registrations_emails .= $reg->mse_cal_workshop_registration_email . ";";
            }
            ?>

            <div class="col-6">

                <div class="card w-100 border-0">
                    <div class="card-body">

                        <h3 class="pb-3"><?php echo $ws_title ?></h3>
                        <div class="pb-3">
                            <p><?php echo get_the_excerpt($_GET["workshop_id"]) ?></p>
                        </div>

                        <h4>Aktionen</h4>
                        <div class="pb-3">
                            <a href="mailto:ExperimentaMakerspace@experimenta-heilbronn.de?bcc=<?php echo $registrations_emails ?>&subject=Informationen zum Workshop '<?php echo $ws_title ?>'" type="button" class="btn btn-outline-secondary">Mail an alle</a>
                        </div>

                        <h4>Anmeldungen</h4>
                        <div class="list-group">
                            <table id="table-registrations" class="table table-striped table-bordered" style="width:100%">
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

                                    <?php if (count($event_registrations) == 0) : ?>
                                        <tr>
                                            <td colspan="5">Aktuell sind keine Anmeldungen für diesen Workshop vorhanden.</td>
                                        </tr>
                                    <?php endif; ?>

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
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('#table-workshops').DataTable();
        $('#table-registrations').DataTable();
    });
</script>