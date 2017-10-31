<?php
/**
 * Template name: price view
 */
use App\RealEstate;


//var_dump(RealEstate::First());
get_header();
?>

<style>
#main {
    padding: 0
}
#mapWrapper {
    height: 600px;
}
#main .fusion-row, #slidingbar-area .fusion-row, .fusion-footer-copyright-area .fusion-row, .fusion-footer-widget-area .fusion-row, .fusion-page-title-row, .tfs-slider .slide-content-container .slide-content {
    max-width: 100%;
}
#main .fusion-row {
    height: 600px;
}
.houseLists {
    top: 100px;
}
.houseLists .containerFixed .housesIndicator {
    position: absolute;
    top: -35px;
}
.fusion-main-menu .fusion-dropdown-menu {
    overflow: visible;
}

</style>


<link rel="stylesheet" type="text/css" href="<?=get_template_directory_uri()?>-Child-Theme/css/fontello.css">
<link rel="stylesheet" type="text/css" href="<?=get_template_directory_uri()?>-Child-Theme/css/price.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<div id="priceContainer">
    <div id="map"></div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js" integrity="sha256-ihAoc6M/JPfrIiIeayPE9xjin4UWjsx2mjW/rtmxLM4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.js"></script>

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f9314f7505ce4d3a8f9e3957b591043e&libraries=services,clusterer,drawing"></script>
<script src="<?=get_template_directory_uri()?>-Child-Theme/js/bundle.js"></script>

<script>
    $(document).ready(function() {
        window.prep = new PeterpanPrice().init();
	var height = $('body').height();
	$('#wrapper').height(height);
	$('#main').height(height);
	$('#priceContainer').height(height);
	$('#map').height(height);


    });
</script>
