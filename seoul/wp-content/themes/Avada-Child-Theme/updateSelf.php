<?php
/**
 * Template name: self update admin view
 */

echo '<pre>';
print_r($_POST);



/*
Array
(
    [birth_date] => 19880515
    [householder] => 세대주
    [sales] => 111
    [address] => 중구
    [home_period_year] => 1
    [home_period_month] => 6
    [married] => 기혼
    [family] => 5
    [qualificatio] => 취준생
    [asset] => 5
    [join_year] => 1
    [join_month] => 6
)
API로 만들어서 저장시키는게 나을듯...
*/

?>
<div class="map">
    <img id="seoulMap" src="http://seoul.peterpanz.com/wp-content/themes/Avada-Child-Theme/images/seoulmap.jpg"
         alt="서울시지도" usemap="#seoul_map">
    <map id="main_image" name="seoul_map">

        <area alt="도봉구" coords="200,26,235,39" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.6400670~37.6974139%7C%7Clongitude:127.0126898~127.0815492&zoomLevel=6&center=%7B%22latitude%22:%2037.66874551052471,%20%22longitude%22:%20127.04710625607225%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="노원구" coords="239,63,274,75" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.6256498~37.6829914%7C%7Clongitude:127.0220216~127.0908749&zoomLevel=6&center=%7B%22latitude%22:%2037.65432571525932,%20%22longitude%22:%20127.05643498087144%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="강북구" coords="189,53,223,67" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.6110195~37.6683793%7C%7Clongitude:126.9910300~127.0598460&zoomLevel=6&center=%7B%22latitude%22:%2037.639704500861555,%20%22longitude%22:%20127.02542475478059%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="중랑구" coords="254,97,288,109" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5776328~37.6349538%7C%7Clongitude:127.0581431~127.1269797&zoomLevel=6&center=%7B%22latitude%22:%2037.606298382938164,%20%22longitude%22:%20127.09254818197658%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="성북구" coords="191,96,225,108" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5607070~37.6180725%7C%7Clongitude:126.9823437~127.0511066&zoomLevel=6&center=%7B%22latitude%22:%2037.589394853812166,%20%22longitude%22:%20127.01671197168382%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="강동구" coords="302,146,336,159" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5014247~37.5587283%7C%7Clongitude:127.0893877~127.1581780&zoomLevel=6&center=%7B%22latitude%22:%2037.530081619411376,%20%22longitude%22:%20127.12376968965768%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="광진구" coords="250,153,284,165" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5098822~37.5672099%7C%7Clongitude:127.0479563~127.1167227&zoomLevel=6&center=%7B%22latitude%22:%2037.53855114671133,%20%22longitude%22:%20127.08232634472337%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="성동구" coords="210,150,243,163" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5347596~37.5921136%7C%7Clongitude:127.0024439~127.0711983&zoomLevel=6&center=%7B%22latitude%22:%2037.56344165945618,%20%22longitude%22:%20127.03680790803972%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="송파구" coords="271,190,304,204" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4858675~37.5431818%7C%7Clongitude:127.0714592~127.1402214&zoomLevel=6&center=%7B%22latitude%22:%2037.51452974041414,%20%22longitude%22:%20127.10582714318203%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="강남구" coords="227,206,262,220" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4886996~37.5460479%7C%7Clongitude:127.0129355~127.0816556&zoomLevel=6&center=%7B%22latitude%22:%2037.51737881609934,%20%22longitude%22:%20127.04728242072986%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="서초구" coords="186,215,219,229" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4550353~37.5124076%7C%7Clongitude:126.9723322~127.0409905&zoomLevel=6&center=%7B%22latitude%22:%2037.483726507246956,%20%22longitude%22:%20127.00664822720324%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="용산구" coords="162,164,196,177" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5038290~37.5612103%7C%7Clongitude:126.9561190~127.0248095&zoomLevel=6&center=%7B%22latitude%22:%2037.53252473271856,%20%22longitude%22:%20126.9904510926435%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="종로구" coords="153,102,188,114" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5442615~37.6016487%7C%7Clongitude:126.9452323~127.0139516&zoomLevel=6&center=%7B%22latitude%22:%2037.57296014538181,%20%22longitude%22:%20126.97957874627731%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="중구" coords="175,137,199,150" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5351143~37.5924911%7C%7Clongitude:126.9631608~127.0318854&zoomLevel=6&center=%7B%22latitude%22:%2037.5638077694201,%20%22longitude%22:%20126.99750990789052%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="은평구" coords="118,82,152,95" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5739962~37.6314125%7C%7Clongitude:126.8947680~126.9634758&zoomLevel=6&center=%7B%22latitude%22:%2037.602709419114426,%20%22longitude%22:%20126.92910868665038%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="서대문구" coords="110,122,155,135" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5505048~37.6079169%7C%7Clongitude:126.9024058~126.9710979&zoomLevel=6&center=%7B%22latitude%22:%2037.57921588236296,%20%22longitude%22:%20126.93673863242319%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="마포구" coords="118,145,151,159" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5375680~37.5950008%7C%7Clongitude:126.8671210~126.9357741&zoomLevel=6&center=%7B%22latitude%22:%2037.56628944010705,%20%22longitude%22:%20126.9014343697771%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="영등포구" coords="102,178,147,192" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4977014~37.5551376%7C%7Clongitude:126.8616731~126.9302855&zoomLevel=6&center=%7B%22latitude%22:%2037.52642456789933,%20%22longitude%22:%20126.89596612713845%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="동대문구" coords="224,118,268,131" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5457877~37.6031399%7C%7Clongitude:127.0053411~127.0741079&zoomLevel=6&center=%7B%22latitude%22:%2037.57446890378152,%20%22longitude%22:%20127.03971130943783%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="동작구" coords="137,196,170,208" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4836886~37.5410997%7C%7Clongitude:126.9051158~126.9737488&zoomLevel=6&center=%7B%22latitude%22:%2037.51239920343266,%20%22longitude%22:%20126.93941918305595%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="관악구" coords="137,233,171,246" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4493872~37.5067916%7C%7Clongitude:126.9171838~126.9857947&zoomLevel=6&center=%7B%22latitude%22:%2037.47809443410969,%20%22longitude%22:%20126.95147610767373%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="금천구" coords="92,238,127,252" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4282873~37.4857242%7C%7Clongitude:126.8614393~126.9299882&zoomLevel=6&center=%7B%22latitude%22:%2037.45701075440468,%20%22longitude%22:%20126.89570067751475%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="구로구" coords="51,205,85,219" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4666966~37.5241379%7C%7Clongitude:126.8532296~126.9218073&zoomLevel=6&center=%7B%22latitude%22:%2037.49542227431101,%20%22longitude%22:%20126.88750533072711%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="양천구" coords="57,179,91,193" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.4882208~37.5456742%7C%7Clongitude:126.8322919~126.9008731&zoomLevel=6&center=%7B%22latitude%22:%2037.51695251191397,%20%22longitude%22:%20126.86656934279654%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
        <area alt="강서구" coords="28,137,61,151" shape="rect"
              href='http://seoul.peterpanz.com/house/search#/?filter=latitude:37.5221453~37.5796082%7C%7Clongitude:126.8152928~126.8838919&zoomLevel=6&center=%7B%22latitude%22:%2037.550881781544724,%20%22longitude%22:%20126.84957919257586%7D&pageSize=90&page=1&type=%EA%B3%B5%EA%B3%B5%EC%9E%84%EB%8C%80'>
    </map>
</div>
