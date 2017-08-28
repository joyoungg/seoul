<?php
/**
 * Template name: map view
 */
use App\RealEstate;

get_header();

//var_dump(RealEstate::First());

?>
    <style>
        #main {
            padding: 0;
        }
        #main div.fusion-row {
            max-width: 100%;
        }
    </style>

    <img src="<?=get_template_directory_uri()?>-Child-Theme/images/map.png" alt="">

<?php
get_footer();


