<?php
/**
 * Template name: map view
 */

use App\RealEstate;


//var_dump(RealEstate::First());
get_header();
?>

<link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>-Child-Theme/css/fontello.css">
<link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>-Child-Theme/css/map.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    body, html {
        overflow: hidden;
    }

    #main {
        padding: 0
    }

    #mapWrapper {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    #main .fusion-row, #slidingbar-area .fusion-row, .fusion-footer-copyright-area .fusion-row, .fusion-footer-widget-area .fusion-row, .fusion-page-title-row, .tfs-slider .slide-content-container .slide-content {
        max-width: 100%;
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
    i.ul-state {
        margin: 0;
    }
    i.ui-state-active {
        border: none !important;
        margin: 0;
    }
    .autoCompleteContainer .autoEach {
        border-color: #eee;
    }
    .autoCompleteContainer .autoEach i {
        top: 0;
    }

</style>

<div id="mapWrapper">
    <div class="left" id="map"></div>
    <div class="right" id="roomList">
        <div class="rightHeader">
            <div class="searchWrapper">
                <div class="searchWrapperInner">
                    <input type="text" id="searchInput"
                           placeholder="지역, 지하철역, 매물번호"
                    />
                </div>
                <button id="serchBtn">검색</button>
            </div>
        </div>
        <div class="filterWrapper">
            <div class="filterHeader toggleFalse">
                <span>조건검색</span>
                <i class="peterpan-down-dir"></i>
                <i class="peterpan-up-dir"></i>
                <span>상세 조건을 선택해주세요</span>
            </div>
            <div class="filterBody peterpanRemove">
                <div class="splitBody">
                    <div class="inBox">
                        <div class="title">
                            보증금
                        </div>
                        <select name="deposit_1">
                            <option value="999">전체</option>
                            <option value="0">0원</option>
                            <option value="1000000">100만원</option>
                            <option value="5000000">500만원</option>
                            <option value="10000000">1천만원</option>
                            <option value="20000000">2천만원</option>
                            <option value="30000000">3천만원</option>
                            <option value="40000000">4천만원</option>
                            <option value="50000000">5천만원</option>
                            <option value="60000000">6천만원</option>
                            <option value="70000000">7천만원</option>
                            <option value="80000000">8천만원</option>
                            <option value="90000000">9천만원</option>
                            <option value="100000000">1억원</option>
                            <option value="120000000">1억2천만원</option>
                            <option value="140000000">1억4천만원</option>
                            <option value="180000000">1억8천만원</option>
                            <option value="200000000">2억</option>
                            <option value="250000000">2억5천만원</option>
                            <option value="300000000">3억원</option>
                        </select>
                        ~
                        <select name="deposit_2">
                            <option value="999">전체</option>
                            <option value="0">0원</option>
                            <option value="1000000">100만원</option>
                            <option value="5000000">500만원</option>
                            <option value="10000000">1천만원</option>
                            <option value="20000000">2천만원</option>
                            <option value="30000000">3천만원</option>
                            <option value="40000000">4천만원</option>
                            <option value="50000000">5천만원</option>
                            <option value="60000000">6천만원</option>
                            <option value="70000000">7천만원</option>
                            <option value="80000000">8천만원</option>
                            <option value="90000000">9천만원</option>
                            <option value="100000000">1억원</option>
                            <option value="120000000">1억2천만원</option>
                            <option value="140000000">1억4천만원</option>
                            <option value="180000000">1억8천만원</option>
                            <option value="200000000">2억</option>
                            <option value="250000000">2억5천만원</option>
                            <option value="300000000">3억원 이상</option>
                        </select>
                    </div>
                    <div class="inBox">
                        월세
                        <div class="checkManagerFee">
                            <div class="iDom">
                                <i class="peterpan-ok-squared"></i>
                            </div>
                            <input type="checkbox" id="addManagerFee">
                            <label for="addManagerFee">관리비 포함</label>
                        </div>
                        <select name="monthly_1">
                            <option value="999">전체</option>
                            <option value="0">0원</option>
                            <option value="100000">10만원</option>
                            <option value="200000">20만원</option>
                            <option value="300000">30만원</option>
                            <option value="400000">40만원</option>
                            <option value="500000">50만원</option>
                            <option value="600000">60만원</option>
                            <option value="700000">70만원</option>
                            <option value="800000">80만원</option>
                            <option value="900000">90만원</option>
                            <option value="1000000">100만원</option>
                            <option value="1100000">110만원</option>
                            <option value="1200000">120만원</option>
                            <option value="1300000">130만원</option>
                            <option value="1400000">140만원</option>
                            <option value="1600000">160만원</option>
                            <option value="1800000">180만원</option>
                            <option value="2000000">200만원</option>
                            <option value="2500000">250만원</option>
                        </select>
                        ~
                        <select name="monthly_2">
                            <option value="999">전체</option>
                            <option value="0">0원</option>
                            <option value="100000">10만원</option>
                            <option value="200000">20만원</option>
                            <option value="300000">30만원</option>
                            <option value="400000">40만원</option>
                            <option value="500000">50만원</option>
                            <option value="600000">60만원</option>
                            <option value="700000">70만원</option>
                            <option value="800000">80만원</option>
                            <option value="900000">90만원</option>
                            <option value="1000000">100만원</option>
                            <option value="1100000">110만원</option>
                            <option value="1200000">120만원</option>
                            <option value="1300000">130만원</option>
                            <option value="1400000">140만원</option>
                            <option value="1600000">160만원</option>
                            <option value="1800000">180만원</option>
                            <option value="2000000">200만원</option>
                            <option value="2500000">250만원 이상</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="title">건물형태</div>
                        <ul class="lists">
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="villaAndHousing"
                                            class="peterpanHidden">
                                    <label for="villaAndHousing">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">빌라/주택</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="officetel"
                                            class="peterpanHidden">
                                    <label for="officetel">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">오피스텔</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="apartment"
                                            class="peterpanHidden">
                                    <label for="apartment">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">아파트</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="storeAndOffice"
                                            class="peterpanHidden">
                                    <label for="storeAndOffice">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">상가/사무실</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="title">매물 종류</div>
                        <ul class="lists">
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="monthly"
                                            class="peterpanHidden">
                                    <label for="monthly">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">월세</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="rental"
                                            class="peterpanHidden">
                                    <label for="rental">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">전세</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="sale"
                                            class="peterpanHidden">
                                    <label for="sale">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">매매</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="shortTerm"
                                            class="peterpanHidden">
                                    <label for="shortTerm">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">단기임대</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="title">방 개수</div>
                        <ul class="lists">
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="oneRoom"
                                            class="peterpanHidden">
                                    <label for="oneRoom">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">원룸</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="twoRoom"
                                            class="peterpanHidden">
                                    <label for="twoRoom">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">투룸</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="threeRoom"
                                            class="peterpanHidden">
                                    <label for="threeRoom">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">쓰리룸 이상</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="title">평수</div>
                        <ul class="lists">
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="is_5"
                                            class="peterpanHidden">
                                    <label for="is_5">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">5평 이하</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="is_5_10"
                                            class="peterpanHidden">
                                    <label for="is_5_10">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">5평 ~ 10평</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="is_10"
                                            class="peterpanHidden">
                                    <label for="is_10">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">10평 이상</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="title">추가옵션</div>
                        <ul class="lists">
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isNewVila"
                                            class="peterpanHidden">
                                    <label for="isNewVila">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">신축</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isfullOption"
                                            class="peterpanHidden">
                                    <label for="isfullOption">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">풀옵션</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isParking"
                                            class="peterpanHidden">
                                    <label for="isParking">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">주차가능</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isElevator"
                                            class="peterpanHidden">
                                    <label for="isElevator">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">엘리베이터</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isPet"
                                            class="peterpanHidden">
                                    <label for="isPet">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">반려동물</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkBoxWrapper">
                                    <input
                                            type="checkbox"
                                            id="isRon"
                                            class="peterpanHidden">
                                    <label for="isRon">
                                        <i class="peterpan-ok-squared"></i>
                                        <span class="inText">전세자금대출</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="bottomContainer">
                    <div class="button filterResetButton">
                        <i class="peterpan-cw"></i>
                        필터 초기화
                    </div>
                    <div class="button filterCloseButton">닫기</div>
                </div>
            </div>
        </div>
        <ul class="categoryWrapper"></ul>
        <div class="houseLists">
            <div class="housesContainer first">
                <div class="housesContent first hidden"></div>
                <div class="housesContent noResult hidden" style="text-align: center;">
                    <img src="http://seoul.peterpanz.com/wp-content/uploads/2015/02/empty-1.png" alt="매물이 없습니다" style="margin-top: 100px">
                </div>
                <div class="housesContent zoomMore hidden" style="text-align: center;">
                    <img src="http://seoul.peterpanz.com/wp-content/uploads/2015/02/zoom.png" alt="매물이 없습니다" style="margin-top: 100px">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"
        integrity="sha256-ihAoc6M/JPfrIiIeayPE9xjin4UWjsx2mjW/rtmxLM4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.js"></script>


<script type="text/javascript"
        src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f9314f7505ce4d3a8f9e3957b591043e&libraries=services,clusterer,drawing"></script>
<script src="<?= get_template_directory_uri() ?>-Child-Theme/js/bundle.js"></script>


<script>
    $(document).ready(function () {
        window.prep = new PeterpanMap().init();
    });
</script>


