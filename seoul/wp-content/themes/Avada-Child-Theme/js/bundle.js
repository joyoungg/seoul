'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PeterpanDetail = function () {
    function PeterpanDetail() {
        var itemId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "336086";

        _classCallCheck(this, PeterpanDetail);

        this._itemId = itemId;
        this._house = null;
        this._images = null;
        this._isModalOn = false;
        this._facilities = null;
        this._agency = null;
        this.utils = Peterpan.Utils;
    }

    _createClass(PeterpanDetail, [{
        key: 'init',
        value: function init() {
            this.getData();
            $(window).on('resize', this.resizeImg);
            this.setModal();
            return this;
        }
    }, {
        key: 'generateMap',
        value: function generateMap() {
            var _house = this._house,
                lat = _house.latitude,
                long = _house.longitude;

            var container = document.getElementById('map');
            var options = {
                center: new daum.maps.LatLng(lat, long),
                level: 5
            };
            var zoomControl = new daum.maps.ZoomControl();

            var map = new daum.maps.Map(container, options);
            var mapTypeControl = new daum.maps.MapTypeControl();
            map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
            map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

            var marker = new daum.maps.Marker({
                map: map,
                position: new daum.maps.LatLng(lat, long)
            });

            marker.setMap(map);
        }
    }, {
        key: 'getData',
        value: function getData() {
            var _this = this;

            $.ajax(this.apiURL).then(function (res) {
                if (res) {
                    res = JSON.parse(res);
                    _this.images = res.house.images;
                    _this._facilities = res.house.detail;
                    _this.house = res.house;
                    _this.agency = JSON.parse(_this.house.user);
                }
            });
        }
    }, {
        key: 'resizeImg',
        value: function resizeImg() {
            var parentNode = void 0;
            if (this._isModalOn) {
                parentNode = $('.modal-dialog');
            } else {
                parentNode = $('.slide_group');
            }
            var WIDTH = parentNode.width();
            var HEIGHT = WIDTH / 1.5;
            $('.slide img').attr('style', 'width:' + WIDTH + 'px; height:' + HEIGHT + 'px');
            $('.slide_viewer').css('height', HEIGHT);
        }
    }, {
        key: 'setSlider',
        value: function setSlider() {
            var parentNode = $('.slide_group');
            var TEMPLATE = '\n            <div class="slide">\n                <img src="#IMG_SRC" />\n            </div>\n        ';
            this.images.forEach(function (_ref) {
                var img_path = _ref.img_path;

                parentNode.append($(TEMPLATE.replace('#IMG_SRC', img_path)));
            });

            var that = this;
            doWork();

            function doWork() {
                $('.slider').each(function () {
                    that.resizeImg();
                    var $this = $(this);
                    var $group = $this.find('.slide_group');
                    var $slides = $this.find('.slide');
                    var bulletArray = [];
                    var currentIndex = 0;
                    var timeout;

                    function move(newIndex) {
                        var animateLeft;
                        var slideLeft;

                        advance();

                        if ($group.is(':animated') || currentIndex === newIndex) {
                            return;
                        }

                        bulletArray[currentIndex].removeClass('active');
                        bulletArray[newIndex].addClass('active');

                        if (newIndex > currentIndex) {
                            slideLeft = '100%';
                            animateLeft = '-100%';
                        } else {
                            slideLeft = '-100%';
                            animateLeft = '100%';
                        }

                        $slides.eq(newIndex).css({
                            display: 'block',
                            left: slideLeft
                        });
                        $group.animate({
                            left: animateLeft
                        }, function () {
                            $slides.eq(currentIndex).css({
                                display: 'none'
                            });
                            $slides.eq(newIndex).css({
                                left: 0
                            });
                            $group.css({
                                left: 0
                            });
                            currentIndex = newIndex;
                        });
                    }

                    function advance() {
                        clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            if (currentIndex < $slides.length - 1) {
                                move(currentIndex + 1);
                            } else {
                                move(0);
                            }
                        }, 4000);
                    }

                    $('.next_btn').on('click', function () {
                        if (currentIndex < $slides.length - 1) {
                            move(currentIndex + 1);
                        } else {
                            move(0);
                        }
                    });

                    $('.previous_btn').on('click', function () {
                        if (currentIndex !== 0) {
                            move(currentIndex - 1);
                        } else {
                            move(3);
                        }
                    });

                    $.each($slides, function (index) {
                        var $button = $('<a class="slide_btn">&bull;</a>');

                        if (index === currentIndex) {
                            $button.addClass('active');
                        }
                        $button.on('click', function () {
                            move(index);
                        }).appendTo('.slide_buttons');
                        bulletArray.push($button);
                    });
                    advance();
                });
            }
        }
    }, {
        key: 'generateTable',
        value: function generateTable() {
            var _house2 = this.house,
                subject = _house2.subject,
                sigudong = _house2.sigudong,
                bedroom_count = _house2.bedroom_count,
                building_type = _house2.building_type,
                bathroom_count = _house2.bathroom_count,
                contract_type = _house2.contract_type,
                supplied_size = _house2.supplied_size,
                real_size = _house2.real_size,
                deposit = _house2.deposit,
                monthly_fee = _house2.monthly_fee,
                floor = _house2.floor,
                floors = _house2.floors,
                maintenance_cost = _house2.maintenance_cost,
                livingroom_form = _house2.livingroom_form,
                maintenance_included = _house2.maintenance_included,
                direction = _house2.direction,
                have_loan = _house2.have_loan,
                door_type = _house2.door_type,
                move_type_string = _house2.move_type_string,
                is_new_building = _house2.is_new_building,
                is_full_option = _house2.is_full_option,
                have_parking_lot = _house2.have_parking_lot,
                have_elevator = _house2.have_elevator,
                support_loan = _house2.support_loan,
                allow_pet = _house2.allow_pet,
                sido = _house2.sido,
                sigungu = _house2.sigungu,
                dong = _house2.dong,
                pp_move_date = _house2.pp_move_date;
            var description = this.house.description;

            description = description.replace(/\n/gi, '<br />');

            var facilitiesMap = {};
            this._facilities.forEach(function (datum) {
                var type = datum.type,
                    facility = datum.facility;


                if (!facilitiesMap[type]) {
                    facilitiesMap[type] = [];
                }

                facilitiesMap[type].push(facility);
            });

            for (var key in facilitiesMap) {
                facilitiesMap[key] = facilitiesMap[key].join(', ');
            }

            function getVerified(idx) {
                if (!idx) return '';
                return '\n                <div class="naver-verified">\n                    <span>\uB124\uC774\uBC84 \uD655\uC778\uB9E4\uBB3C</span>\n                    <span>\n                        <a href="http://land.naver.com/article/articleDetailInfo.nhn?atclNo=' + idx + '" target="_blank">\n                            [' + idx + '] \uB9E4\uBB3C\uC740 \uB124\uC774\uBC84 \uAC80\uC99D\uC744 \uD1B5\uD574 \uD655\uC778\uB41C \uB9E4\uBB3C\uC785\uB2C8\uB2E4.\n                        </a>\n                    </span>\n                </div>\n            ';
            }

            var TEMPLATE = '\n            <div class="houseContentsWrapper">\n                <h1 class="subject">' + subject + '</h1>\n                ' + getVerified(this.house.idx_naver) + '\n            </div>\n            <div class="detail">\n                <div class="detailTable">\n                    <h3 class="houseTitle">\n                        <span>\uB9E4\uBB3C\uC815\uBCF4</span>\n                    </h3>\n                    <div class="commonHouse">\n                        <div class="row borderTop first">\n                            <div class="col-md-2 col-xs-4 column">\n                                \uC8FC\uC18C\n                            </div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">\n                                ' + sido + ' ' + sigungu + ' ' + dong + '\n                            </div>\n                            <div class="col-md-3 col-xs-4 column left-padding-20">\n                                \uBC29 \uAC1C\uC218\n                            </div>\n                            <div class="col-md-3 col-xs-8 column value">\n                                ' + (bedroom_count || '-') + '\uAC1C\n                            </div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uAC74\uBB3C\uD615\uD0DC</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + building_type + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uC695\uC2E4\uAC1C\uC218</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (bathroom_count || '-') + '\uAC1C</div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uACC4\uC57D\uD615\uD0DC</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + contract_type + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uACF5\uAE09/\uC804\uC6A9\uBA74\uC801</div>\n                            <div class="col-md-4 col-xs-8 column value">\n                                ' + Math.round(supplied_size) + 'm<sup>2</sup>(' + Math.round(supplied_size / 3.3) + 'P) / ' + Math.round(real_size) + 'm<sup>2</sup>(' + Math.round(real_size / 3.3) + 'P)\n                            </div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uAC00\uACA9\uC815\uBCF4</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + this.utils.convertMoney(deposit) + (Number(monthly_fee) ? '/' + this.utils.convertMoney(monthly_fee) : '') + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uD574\uB2F9\uCE35/\uC804\uCCB4\uC99D</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (floor || '-') + '\uCE35/' + (floors || '-') + '\uCE35</div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uAD00\uB9AC\uBE44</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (Number(maintenance_cost) ? maintenance_cost : '-') + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uBC29 \uAC70\uC2E4 \uD615\uD0DC</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (livingroom_form || '-') + '</div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uAD00\uB9AC\uBE44\uD3EC\uD568\uB0B4\uC5ED</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (maintenance_included || '-') + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uBC29\uD5A5</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (direction || '-') + '</div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uC735\uC790\uAE08</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (have_loan || '없음') + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uD604\uAD00 \uC720\uD615</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (door_type || '-') + '</div>\n                        </div>\n                        <div class="row borderTop last">\n                            <div class="col-md-2 col-xs-4 column">\uC785\uC8FC\uAC00\uB2A5\uC77C</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + function () {
                if (pp_move_date) {
                    return pp_move_date;
                } else {
                    return '협의';
                }
            }() + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uC900\uACF5\uB144\uC6D4</div>\n                            <div class="col-md-4 col-xs-8 column value">-</div>\n                        </div>\n                        <h3 class="additionalTitle">\uCD94\uAC00\uC635\uC158</h3>\n\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_new' + (is_new_building ? '' : '_x') + '.png" />\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_full' + (is_full_option ? '' : '_x') + '.png" />\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_park' + (have_parking_lot ? '' : '_x') + '.png" />\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_ev' + (have_elevator ? '' : '_x') + '.png" />\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_animal' + (allow_pet ? '' : '_x') + '.png" />\n                        <img src="/wp-content/themes/Avada-Child-Theme/images/detail_loan' + (support_loan ? '' : '_x') + '.png" />\n                    </div>\n                </div>\n                <div class="detailTable info">\n                    <h3 class="title">\uC2DC\uC124\uC815\uBCF4</h3>\n                    <div class="commonHouse">\n                        <div class="row borderTop first">\n                            <div class="col-md-2 col-xs-4 column">\uB09C\uBC29\uBC29\uC2DD</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (facilitiesMap['난방방식'] || '-') + '</div>\n                            <div class="col-md-3 col-xs-4 column left-padding-20">\uB0C9\uBC29\uC2DC\uC124</div>\n                            <div class="col-md-3 col-xs-8 column value">' + (facilitiesMap['냉방시설'] || '-') + '</div>\n                        </div>\n                        <div class="row borderTop">\n                            <div class="col-md-2 col-xs-4 column">\uC0DD\uD65C\uC2DC\uC124</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (facilitiesMap['생활시설'] || '-') + '</div>\n                            <div class="col-md-2 col-xs-4 column left-padding-20">\uBCF4\uC548\uC2DC\uC124</div>\n                            <div class="col-md-4 col-xs-8 column value">' + (facilitiesMap['보안시설'] || '-') + '</div>\n                        </div>\n                        <div class="row borderTop last">\n                            <div class="col-md-2 col-xs-4 column">\uAE30\uD0C0\uC2DC\uC124</div>\n                            <div class="col-md-4 col-xs-8 column value right-padding-30">' + (facilitiesMap['기타시설'] || '-') + '</div>\n                        </div>\n                    </div>\n                </div>\n                <div class="description">\n                    <h3>\uC0C1\uC138\uC124\uBA85</h3>\n                    <div class="roundBox">' + (description || '-') + '</div>\n                </div>\n                <div class="description">\n                    <h3>\uC704\uCE58\uC815\uBCF4</h3>\n                    <div class="row">\n                        <div id="map"></div>\n                    </div>\n                </div>\n            </div>\n        ';

            $('.leftWrapper').append($(TEMPLATE));
        }
    }, {
        key: 'generateSidebar',
        value: function generateSidebar() {
            var _this2 = this;

            var _agency = this._agency,
                info = _agency.info,
                mapping_number = _agency.mapping_number,
                type = _agency.type;
            var _house3 = this.house,
                sale_num = _house3.sale_num,
                deposit = _house3.deposit,
                monthly_fee = _house3.monthly_fee,
                sido = _house3.sido,
                sigungu = _house3.sigungu,
                dong = _house3.dong;


            var TEMPLATE = void 0;

            switch (type) {
                case '중개':
                    TEMPLATE = '\n                    <div class="sidebar-content">\n                        <div class="header">\n                            <div class="trade-type">\uC911\uAC1C</div>\n                            <div class="sale-num">\uB9E4\uBB3C\uBC88\uD638 ' + sale_num + '</div>\n                        </div>\n                        <div class="detail">\n                            <span class="typeHolder rent"></span>\n                            <span class="priceHolder">' + function () {
                        var str = '';

                        str += _this2.utils.convertMoney(deposit);
                        if (monthly_fee != 0) {
                            str += '/';
                            str += _this2.utils.convertMoney(monthly_fee);
                        }
                        return str;
                    }() + '</span>\n                        </div>\n                        <div class="location">' + sido + ' ' + sigungu + ' ' + dong + '</div>\n                        <div class="agency">\n                            <div class="title">' + info['중개사'] + '</div>\n                            <div class="president">\uB300\uD45C\uC790: ' + info['대표자'] + '</div>\n                            <div class="safe_contact">\uC548\uC2EC\uBC88\uD638: ' + mapping_number + '</div>\n                        </div>\n                    </div>\n                ';

                    $('.sidebar').append($(TEMPLATE));
                    break;
                case '개인':
                case '서울시':
                    TEMPLATE = '\n                    <div class="sidebar-content">\n                        <div class="header">\n                            <div class="trade-type">\uAC1C\uC778</div>\n                            <div class="sale-num">\uB9E4\uBB3C\uBC88\uD638 ' + sale_num + '</div>\n                        </div>\n                        <div class="detail">\n                            <span class="typeHolder rent"></span>\n                            <span class="priceHolder">' + function () {
                        var str = '';

                        str += _this2.utils.convertMoney(deposit);
                        if (monthly_fee != 0) {
                            str += '/';
                            str += _this2.utils.convertMoney(monthly_fee);
                        }
                        return str;
                    }() + '</span>\n                        </div>\n                        <div class="agency">\n                            ' + function () {
                        var str = '';
                        for (var key in info) {
                            str += '<div class="president">' + key + ': ' + info[key] + '</div>';
                        }
                        return str;
                    }() + '\n                            <div class="safe_contact">\uC548\uC2EC\uBC88\uD638: ' + mapping_number + '</div>\n                        </div>\n                    </div>\n                ';

                    $('.sidebar').append($(TEMPLATE));
                    break;
            }
        }
    }, {
        key: 'setModal',
        value: function setModal() {
            var _this3 = this;

            var $modal = $('#myModal');
            var $body = $modal.find('.modal-content');
            var $slider = $('.sliderContainer');
            var $leftWrapper = $('.leftWrapper');

            $('.slide_viewer').delegate('img', 'click', function () {
                $modal.modal('show');
            });

            $modal.on('hide.bs.modal', function () {
                _this3._isModalOn = false;
                $leftWrapper.prepend($slider);
                _this3.resizeImg();
            });
            $modal.on('show.bs.modal', function () {
                _this3._isModalOn = true;
                $body.append($slider);
                _this3.resizeImg();
            });
        }
    }, {
        key: 'house',
        get: function get() {
            return this._house;
        },
        set: function set(house) {
            this._house = house;
            this.generateTable();
            this.generateMap();
        }
    }, {
        key: 'images',
        get: function get() {
            return this._images;
        },
        set: function set(images) {
            this._images = images;
            this.setSlider();
        }
    }, {
        key: 'apiURL',
        get: function get() {
            //return `http://api.peterpanz.com/get_detail/${this._itemId}`;
            return 'http://api.seoul.peterpanz.com/house?hidx=' + this._itemId;
        }
    }, {
        key: 'agency',
        set: function set(data) {
            this._agency = data;
            this.generateSidebar();
        }
    }]);

    return PeterpanDetail;
}();
'use strict';

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PeterpanMap = function () {
    function PeterpanMap() {
        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        _classCallCheck(this, PeterpanMap);

        this.utils = Peterpan.Utils;
        this._map = null;
        this._clusterer = null;
        this._data = null;
        this._activeDom = null;
        this._zoomLevel = undefined;
        this._activeMarker = null;
        this._query = this.getDefaultQuery();
        this.getHouses = this.debounce(this.getHouses, 250);
        this.handleComplete = this.debounce(this.handleComplete, 200);
        this.setFilterToggle = this._setFilterToggle();
        this._markers = [];
        this._isLoading = false;
        this._isDragging = false;
        this._categories = null;
        this._renderType = 'cluster';
        this._houseMarkers = [];
    }

    _createClass(PeterpanMap, [{
        key: 'setLocalStorage',
        value: function setLocalStorage() {
            if (window.localStorage) {
                localStorage.setItem('peterpanQuery', JSON.stringify(this._query));
            }
        }
    }, {
        key: 'getDefaultQuery',
        value: function getDefaultQuery() {
            return {
                checkDeposit: [999, 999],
                checkMonth: [999, 999],
                buildingType: [],
                contractType: [],
                roomType: [],
                roomCount_etc: [],
                realSize: [],
                additional_options: [],
                isManagerFee: [],
                zoomLevel: 5,
                pageIndex: 1,
                pageSize: 90,
                center: {
                    x: 37.5667295445299,
                    y: 126.97835799073495
                },
                category: null
            };
        }
    }, {
        key: 'convert',
        value: function convert(key, value) {
            var converters = {
                checkDeposit: rangeFunc,
                checkMonth: rangeFunc,
                buildingType: arrayFunc,
                contractType: arrayFunc,
                roomType: arrayFunc,
                roomCount_etc: arrayFunc,
                realSize: arrayFunc,
                additional_options: arrayFunc,
                isManagerFee: arrayFunc
            };

            if (!converters[key]) return '';
            return converters[key](value);

            function rangeFunc(_ref) {
                var _ref2 = _slicedToArray(_ref, 2),
                    val1 = _ref2[0],
                    val2 = _ref2[1];

                if (val1 === 999 && val2 === 999) {
                    return '';
                }return '||' + key + ':' + val1 + '~' + val2;
            }

            function arrayFunc() {
                var arr = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];

                if (!arr.length) return '';
                return '||' + key + ';[' + getStringArray(arr) + ']';
            }

            function getStringArray(arr) {
                var str = '';
                arr.forEach(function (v, index) {
                    str += '"' + v + '"';
                    if (index !== arr.length - 1) {
                        str += ',';
                    }
                });
                return str;
            }
        }
    }, {
        key: 'hideActiveMarker',
        value: function hideActiveMarker() {
            this._activeMarker.setPosition(new daum.maps.LatLng(0, 0));
        }
    }, {
        key: 'init',
        value: function init() {
            var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

            this.getInitialRouteParams(options);
        }
    }, {
        key: '_init',
        value: function _init() {
            var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

            this.generateCategories();
            this.setPadding();
            this.setScroll();
            this.setAutoComplete();
            this.setFilterToggle();
            this.generateMap(options.id);
            this.generateClusterer();
            this.setRouteParams();
            this.setInputsValue();
            this.setTooltips();
            return this;
        }
    }, {
        key: 'setTooltips',
        value: function setTooltips() {
            $(document).tooltip({
                items: "h3",
                content: function content() {
                    var elem = $(this);
                    var value = elem.attr('tooltip-data') || elem.text();
                    return '\n                <div class="peterpanTooltip">\n                    ' + value + '\n                </div>\n            ';
                }
            });
        }
    }, {
        key: 'setPadding',
        value: function setPadding() {
            var elem = $('.fusion-header');
            if (!elem || !elem.length) return;

            var value = elem.offset().top + elem.height();
            $('#mapWrapper').css('padding-top', value);
        }
    }, {
        key: 'getInitialRouteParams',
        value: function getInitialRouteParams(options) {
            var _this = this;

            var that = this;
            var localQuery;
            if (window.localStorage) {
                localQuery = localStorage.getItem('peterpanQuery');
            }
            var urlQuery = decodeURI(location.hash);
            if (urlQuery) {
                urlQuery = urlQuery.split('#/?')[1];
            }

            $.ajax('http://api.seoul.peterpanz.com/list?type=type').then(function (res) {
                if (res) {
                    res = JSON.parse(res);
                    that._categories = res.type;
                    that._query.category = that._categories[0];
                    return;
                }
            }).then(function () {
                if (localQuery && localQuery !== 'undefined' && false) {
                    _this._query = JSON.parse(localQuery);
                    return _this._init();
                } else if (urlQuery) {
                    $.ajax('http://api.seoul.peterpanz.com/filter?' + urlQuery).then(function (res) {
                        if (!res) return _this._init();else {
                            var result = JSON.parse(res);
                            var center = result.center,
                                zoomLevel = result.zoomLevel,
                                type = result.type,
                                or = result.or,
                                between = result.between,
                                _in = result._in;


                            if (center) {
                                center = JSON.parse(center);
                                _this._query.center = {
                                    x: Number(center.latitude),
                                    y: Number(center.longitude)
                                };
                            }

                            if (zoomLevel) _this._query.zoomLevel = Number(zoomLevel);
                            if (type) _this._query.category = type;

                            if (or) {
                                if (or.real_size) {
                                    or.real_size.forEach(function (_ref3) {
                                        var _ref4 = _slicedToArray(_ref3, 2),
                                            val1 = _ref4[0],
                                            val2 = _ref4[1];

                                        var ret = val1 + val2;
                                        var realSizeStr = void 0;
                                        if (ret === 5) realSizeStr = "5평 이하";else if (ret === 15) realSizeStr = "5평 ~ 10평";else realSizeStr = "10평 이상";
                                        _this._query.realSize.push(realSizeStr);
                                    });
                                }
                            }
                            if (between) {
                                if (between.deposit) {
                                    _this._query.checkDeposit = between.deposit.map(Number);
                                }
                                if (between.monthly_fee) {
                                    _this._query.checkMonth = between.monthly_fee.map(Number);
                                }
                                if (between.is_manager_fee) {
                                    _this._query.isManagerFee = between.is_manager_fee;
                                }
                            }
                            if (_in) {
                                if (_in.building_type) {
                                    _this._query.buildingType = _in.building_type;
                                }
                                if (_in.contract_type) {
                                    _this._query.contractType = _in.contract_type;
                                }
                                if (_in.room_type) {
                                    _this._query.roomType = _in.room_type;
                                }
                                if (_in.additional_options) {
                                    _this._query.additional_options = _in.additional_options;
                                }
                            }
                            return _this._init();
                        }
                    });
                } else {
                    return _this._init();
                }
            });
        }
    }, {
        key: 'generateMap',
        value: function generateMap() {
            var _this2 = this;

            var id = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'map';
            var _query = this._query,
                _query$center = _query.center,
                x = _query$center.x,
                y = _query$center.y,
                level = _query.zoomLevel;

            var map = new daum.maps.Map(document.getElementById(id), {
                center: new daum.maps.LatLng(x, y),
                level: level
            });
            this._map = map;
            var customOverlay = new daum.maps.CustomOverlay({
                position: new daum.maps.LatLng(0, 0),
                content: '<div class="activeMarker"></div>'
            });

            customOverlay.setMap(map);
            this._activeMarker = customOverlay;

            daum.maps.event.addListener(map, 'bounds_changed', function () {
                _this2._query.zoomLevel = _this2._map.getLevel();
                _this2._query.pageIndex = 1;
                _this2.setRouteParams();
            });

            daum.maps.event.addListener(map, 'drag', function () {
                _this2._isDragging = true;
            });

            daum.maps.event.addListener(map, 'dragend', function () {
                _this2._isDragging = false;
                _this2.setRouteParams();
            });

            return map;
        }
    }, {
        key: 'generateClusterer',
        value: function generateClusterer() {
            var _this3 = this;

            var clusterer = new daum.maps.MarkerClusterer({
                map: this._map,
                gridSize: 40,
                averageCenter: true,
                minLevel: 0,
                minClusterSize: 1,
                disableClickZoom: true,
                styles: [{
                    width: '50px',
                    height: '50px',
                    background: '#fff',
                    color: '#1BC8C2',
                    borderRadius: '25px',
                    textAlign: 'center',
                    lineHeight: '45px',
                    boxShadow: '1px 1px 1px #ccc',
                    border: '4px solid #fff'
                }]
            });

            var event = daum.maps.event;


            event.addListener(clusterer, 'clusterclick', function (cluster) {
                _this3.activeDom = cluster._content;
                var markers = cluster._markers.map(function (m) {
                    return m.datum;
                });
                _this3.renderList(markers);
            });

            event.addListener(clusterer, 'clusterover', function (_ref5) {
                var _content = _ref5._content;

                $(_content).addClass('activeCluster');
            });

            event.addListener(clusterer, 'clusterout', function (_ref6) {
                var _content = _ref6._content;

                if (_this3.activeDom !== _content) $(_content).removeClass('activeCluster');
            });

            this._clusterer = clusterer;
        }
    }, {
        key: 'setHouseMarkers',
        value: function setHouseMarkers() {
            var _this4 = this;

            this.resetClusters();

            var map = this._map;
            var data = this._data;
            var zIndex = 0;
            data.forEach(function (v) {
                var latitude = v.latitude,
                    longitude = v.longitude,
                    html = v.html;


                html = $(html);

                var custom = new daum.maps.CustomOverlay({
                    position: new daum.maps.LatLng(Number(latitude), Number(longitude)),
                    content: html[0]
                });

                html.on('mouseover', function () {
                    custom.setZIndex(zIndex++);
                });

                (function (latitude, longitude) {
                    html.on('click', function () {
                        _this4.moveTo({ latitude: latitude, longitude: longitude }, 4);
                    });
                })(latitude, longitude);

                custom.setMap(map);
                _this4._houseMarkers.push(custom);
            });
        }
    }, {
        key: 'resetClusters',
        value: function resetClusters() {
            if (this._clusterer) this._clusterer.clear();
            if (this._houseMarkers) {
                this._houseMarkers.forEach(function (m) {
                    m.setMap(null);
                });
                this._houseMarkers = [];
            }
        }
    }, {
        key: 'setClusters',
        value: function setClusters() {
            this.resetClusters();

            var that = this;

            var markers = this.data.map(function (h) {
                var latitude = h.latitude,
                    longitude = h.longitude;

                var marker = new daum.maps.Marker({
                    map: that._map,
                    position: new daum.maps.LatLng(latitude, longitude)
                });
                marker.datum = h;
                return marker;
            });

            this._clusterer.addMarkers(markers);
        }
    }, {
        key: 'resetList',
        value: function resetList() {
            $('.housesContent.first *').remove();
            $('.housesContent').addClass('hidden');
        }
    }, {
        key: 'renderList',
        value: function renderList(list) {
            var that = this;

            this.resetList();

            if (!this.data || !this.data.length) {
                $('.housesContent.noResult').removeClass('hidden');
            } else if (this._renderType === 'cluster') {
                var _generateView = function _generateView(house) {
                    var parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : parentNode;
                    var contract_type = house.contract_type,
                        hidx = house.hidx,
                        img_path = house.img_path,
                        deposit = house.deposit,
                        monthly_fee = house.monthly_fee,
                        subject = house.subject,
                        building_type = house.building_type,
                        building_form = house.building_form,
                        maintenance_cost = house.maintenance_cost,
                        floor = house.floor,
                        badge = '',
                        badgeStyle = '';

                    if (house.is_zero == 1) {
                        badge += '<li class="zero">ZERO부동산</li>';
                    }
                    if (house.is_safe == 1) {
                        badge += '<li class="safe">안심직거래</li>';
                    }
                    if (house.idx_naver) {
                        badge += '<li class="naver">네이버 확인매물</li>';
                    }


                    var convertedTemplate = template.replace('%HOUSE_ID', hidx).replace('%IMAGE_PATH', img_path).replace('%HOUSE_BADGE', badge).replace('%HOUSE_PRICE', function () {
                        var str = '';
                        str += that.utils.convertMoney(deposit);
                        if (monthly_fee != 0) {
                            str = str + '/' + that.utils.convertMoney(monthly_fee);
                        }
                        return str;
                    }).replace('%HOUSE_DESC', subject).replace('%HOUSE_TYPE', function () {
                        return '\n                            ' + building_type + ' ' + (ROOM_MAP[building_form] ? '| ' + ROOM_MAP[building_form] : '') + '\n                            ' + (maintenance_cost != 0 ? '| 관리비 ' + that.utils.convertMoney(maintenance_cost) + '만원' : '') + '\n                            ' + (floor != 0 ? '| ' + floor + '층' : '') + '\n                        ';
                    }()).replace(/%CONTRACT_TYPE/gi, contract_type);

                    var dom = $(convertedTemplate);
                    (function (h) {
                        dom.mouseenter(function () {
                            var pos = new daum.maps.LatLng(h.latitude, h.longitude);
                            that._activeMarker.setPosition(pos);
                            that._activeMarker.setZIndex(9999);
                        });

                        dom.mouseleave(function () {
                            that.hideActiveMarker();
                        });
                    })(house);
                    parent.append(dom);
                };

                $('.housesContent.first').removeClass('hidden');
                var template = '' +
                    '<div class="houseEach">\n' +
                    '    <a href="/detail?houseId=%HOUSE_ID" target="_black">\n' +
                    '        <div class="houseImage" style="background-image: url(\'%IMAGE_PATH\')" />\n' +
                    '        <div class="houseContentContainer">\n' +
                    '            <div class="houseContractType" data-contract-type=%CONTRACT_TYPE>%CONTRACT_TYPE</div>\n' +
                    '            <div class="housePrice">%HOUSE_PRICE</div>\n' +
                    '            <div class="houseDesc">%HOUSE_DESC</div>\n' +
                    '            <div class="houseType">%HOUSE_TYPE</div>\n' +
                    '            <div class="houseBadge"><ul class="badge">%HOUSE_BADGE</ul></div>' +
                    '        </div>\n' +
                    '    </a>\n' +
                    '</div>\n';

                var ROOM_MAP = {
                    '원룸': '방 1개',
                    '투룸': '방 2개'
                };

                var parentNode = void 0;
                if (list) {
                    parentNode = $('.housesContent.first').eq(0);
                    list.forEach(function (v) {
                        _generateView(v, parentNode);
                    });
                } else {
                    var categoryData = this.data;


                    parentNode = $('.housesContent.first').eq(0);
                    categoryData.forEach(function (v) {
                        _generateView(v, parentNode);
                    });
                }
            } else if (this._renderType === 'overlay' && this._query.zoomLevel < 8) {
                $('.housesContent.first').removeClass('hidden');
                var _parentNode = $('.housesContent.first').eq(0);
                this._data.forEach(function (d) {
                    _parentNode.append($(d.html));
                });
            } else {
                $('.housesContent.zoomMore').removeClass('hidden');
            }

            setTimeout(function () {
                that.scrollTo(0);
            }, 300);
        }
    }, {
        key: 'getMapInfo',
        value: function getMapInfo() {
            var _map$getBounds = this._map.getBounds(),
                ja = _map$getBounds.ja,
                ia = _map$getBounds.ia,
                ha = _map$getBounds.ha,
                ca = _map$getBounds.ca;

            return {
                ja: Number(ja).toFixed(7),
                ia: Number(ia).toFixed(7),
                ca: Number(ca).toFixed(7),
                ha: Number(ha).toFixed(7)
            };
        }
    }, {
        key: 'getAutoCompleteSource',
        value: function getAutoCompleteSource(term, cb) {
            var _this5 = this;

            $.ajax('http://api.seoul.peterpanz.com/search?keyword=' + term).then(function (res) {
                if (res) {
                    var data = void 0;
                    var ret = JSON.parse(res).list;

                    if (typeof ret.house === 'number') {
                        return cb([{
                            label: ret.house,
                            cb: function cb() {
                                window.open('/detail?houseId=' + ret.house, '_blank');
                            }
                        }]);
                    }

                    var count = _.reduce(ret, function (result, value, key) {
                        return result + value.length;
                    }, 0);
                    if (!count) {
                        data = [{
                            label: "<img src=\"http://seoul.peterpanz.com/wp-content/uploads/2015/02/empty-1.png\" width=\"133\" height=\"21\" alt=\"매물이 없습니다\">",
                            id: _this5.utils.getHash(),
                            class: 'noResult'
                        }];
                    } else {
                        var dong = ret.dong,
                            subway = ret.subway,
                            university = ret.university,
                            house = ret.house;


                        data = [].concat(_toConsumableArray(dong.map(function (d) {
                            var sido = d.sido,
                                gugun = d.gugun,
                                dong = d.dong,
                                latitude = d.latitude,
                                longitude = d.longitude;

                            return {
                                label: sido + ' ' + gugun + ' ' + dong,
                                icon: "location",
                                id: _this5.utils.getHash(),
                                locatoin: {
                                    latitude: latitude, longitude: longitude
                                }
                            };
                        })));

                        data = [].concat(_toConsumableArray(data), _toConsumableArray(subway.map(function (d) {
                            var station = d.station,
                                line = d.line,
                                longitude = d.longitude,
                                latitude = d.latitude;

                            return {
                                label: station + '(' + line + ')',
                                icon: "subway",
                                id: _this5.utils.getHash(),
                                locatoin: {
                                    latitude: latitude, longitude: longitude
                                }
                            };
                        })));

                        data = [].concat(_toConsumableArray(data), _toConsumableArray(university.map(function (d) {
                            var name = d.name,
                                longitude = d.longitude,
                                latitude = d.latitude;

                            return {
                                label: '' + name,
                                icon: "college",
                                id: _this5.utils.getHash(),
                                locatoin: {
                                    latitude: latitude, longitude: longitude
                                }
                            };
                        })));
                    }

                    //TODO house?
                    cb(data);
                }
            });
        }
    }, {
        key: 'setAutoComplete',
        value: function setAutoComplete() {
            var _this6 = this;

            var dom = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $('#searchInput');

            var elem = dom.autocomplete({
                minLength: 2,
                classes: {
                    'ui-autocomplete': 'ui-autocomplete autoCompleteContainer'
                },
                source: function source(req, res) {
                    _this6.getAutoCompleteSource(req.term, res);
                },
                focus: function focus(event, ui) {
                    event.preventDefault();
                    $('.autoEachFocused').removeClass('autoEachFocused');
                    $('#' + ui.item.id).addClass('autoEachFocused');
                },
                select: function select(event, _ref7) {
                    var item = _ref7.item;

                    if (item.cb) return item.cb();else return _this6.moveTo(item.location || item.locatoin);
                },
                appendTo: '.searchWrapperInner'
            });

            elem.autocomplete("instance")._renderItem = function (ul, item) {
                return $('<li class=\'autoEach ' + item.class + '\' id=\'' + item.id + '\'>').append("<span>" + item.label + "</span>" + ('<i class=\'peterpan-' + item.icon + '\'></i>')).appendTo(ul);
            };
            $('#serchBtn').on('click', function (e) {
                var elem = $('#searchInput');
                if (elem.val().length > 1) elem.autocomplete('search', elem.val());
            });
        }
    }, {
        key: 'handleFilter',
        value: function handleFilter(id, value) {
            var elem = $('#' + id);
            value = value || elem[0].checked;
            var parentElem = elem.parent();
            var query = this._query;
            var textValue = elem.next().children().eq(1).text();
            var keyName = void 0;

            switch (id) {
                case 'villaAndHousing':case 'officetel':
                case 'apartment':case 'storeAndOffice':
                    keyName = 'buildingType';
                    break;
                case 'monthly':case 'rental':
                case 'sale':case 'shortTerm':
                    keyName = 'contractType';
                    break;
                case 'oneRoom':case 'twoRoom':case 'threeRoom':
                    keyName = 'roomType';
                    break;
                case 'is_5':case 'is_5_10':case 'is_10':
                    keyName = 'realSize';
                    break;
                case 'isNewVila':case 'isfullOption':case 'isParking':
                case 'isElevator':case 'isPet':case 'isRon':
                    keyName = 'additional_options';
                    break;
                default:
                    keyName = 'isManagerFee';
                    textValue = "add";
            }
            if (value) {
                query[keyName].push(textValue);
                query[keyName] = _.uniq(query[keyName]);
                parentElem.addClass('selected');
            } else {
                query[keyName] = _.remove(query[keyName], function (v) {
                    return v !== textValue;
                });
                parentElem.removeClass('selected');
            }

            this.setRouteParams();
        }
    }, {
        key: '_setFilterToggle',
        value: function _setFilterToggle() {
            var _this7 = this;

            var _visible = false;
            var elem = $('.filterHeader');
            var targetElem = $('.filterBody');
            var closeElem = $('.filterCloseButton');
            var resetElem = $('.filterResetButton');

            var filters = $('.filterBody input:checkbox');
            var selectors = $('.splitBody select');

            return function () {
                filters.change(function (event) {
                    _this7.handleFilter($(event.currentTarget).attr('id'));
                });

                selectors.change(function (event) {
                    var target = $(event.currentTarget);
                    var targetValue = Number(target.val());
                    var rightValue = void 0;
                    var leftValue = void 0;
                    switch (target.attr('name')) {
                        case 'deposit_1':
                            rightValue = _this7._query.checkDeposit[1];
                            if (rightValue !== 999) {
                                _this7._query.checkDeposit[0] = Math.min(rightValue, targetValue);
                                target.val(_this7._query.checkDeposit[0]);
                            } else {
                                _this7._query.checkDeposit[0] = targetValue;
                            }
                            break;
                        case 'deposit_2':
                            leftValue = _this7._query.checkDeposit[0];
                            if (leftValue !== 999) {
                                _this7._query.checkDeposit[1] = Math.max(leftValue, targetValue);
                                target.val(_this7._query.checkDeposit[1]);
                            } else {
                                _this7._query.checkDeposit[1] = targetValue;
                            }
                            break;
                        case 'monthly_1':
                            rightValue = _this7._query.checkMonth[1];
                            if (rightValue !== 999) {
                                _this7._query.checkMonth[0] = Math.min(rightValue, targetValue);
                                target.val(_this7._query.checkMonth[0]);
                            } else {
                                _this7._query.checkMonth[0] = targetValue;
                            }
                            break;
                        case 'monthly_2':
                            leftValue = _this7._query.checkMonth[0];
                            if (leftValue !== 999) {
                                _this7._query.checkMonth[1] = Math.max(leftValue, targetValue);
                                target.val(_this7._query.checkMonth[1]);
                            } else {
                                _this7._query.checkMonth[1] = targetValue;
                            }
                            break;
                    }
                    _this7.setRouteParams();
                });

                resetElem.on('click', function () {
                    var query = _this7.getDefaultQuery();
                    var _query2 = _this7._query,
                        zoomLevel = _query2.zoomLevel,
                        center = _query2.center,
                        category = _query2.category;

                    query.zoomLevel = zoomLevel;
                    query.center = center;
                    query.category = category;
                    _this7._query = query;
                    $('.filterBody select').val('999');
                    $('.filterBody .selected').removeClass('selected');
                    _this7.setRouteParams();
                });

                elem.on('click', function () {
                    if (_this7._renderType !== 'cluster') { alert('조건검색 기능을 지원하지 않습니다'); return; }
                    _visible = !_visible;
                    doWork(_visible);
                });

                closeElem.on('click', function () {
                    _visible = false;
                    doWork(_visible);
                });

                function doWork(visible) {
                    if (visible) {
                        targetElem.removeClass('peterpanRemove');
                        elem.addClass('toggleTrue');
                        elem.removeClass('toggleFalse');
                        targetElem.stop(true, true);
                        targetElem.animate({
                            height: $('#roomList').height() - 86
                        }, 400);
                    } else {
                        targetElem.stop(true, true);
                        targetElem.animate({
                            height: 0
                        }, 400, function () {
                            elem.removeClass('toggleTrue');
                            elem.addClass('toggleFalse');
                        });
                    }
                }
            };
        }
    }, {
        key: 'moveTo',
        value: function moveTo(_ref8, zoomLevel) {
            var latitude = _ref8.latitude,
                longitude = _ref8.longitude;

            var LATLNG = new daum.maps.LatLng(latitude, longitude);
            this._map.setCenter(LATLNG);
            this._map.setLevel(zoomLevel || 6);
        }
    }, {
        key: 'getHouses',
        value: function getHouses(isAdd) {
            var _this8 = this;

            if (this._isLoading || this._isDragging) return;

            this.setLocalStorage();
            this._isLoading = true;
            if (isAdd) this._query.pageIndex++;
            var URL = this.API_URL_GET_HOUSES;
            $.ajax({
                type: "GET",
                url: URL,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8"
            }).then(function (res) {
                if (!_.isEmpty(res)) {
                    res = JSON.parse(res);
                    if (_.isEmpty(res.list)) {
                        _this8.data = [];
                    } else if (res.list['공공임대']) {
                        _this8._renderType = 'overlay';
                        _this8.data = res.list['공공임대'];
                    } else {
                        _this8._renderType = 'cluster';
                        var ret = res.list && res.list[_this8._query.category] && res.list[_this8._query.category].data;
                        if (!_.isEmpty(ret)) {
                            if (isAdd) {
                                _this8.data = [].concat(_toConsumableArray(_this8.data), _toConsumableArray(ret));
                            } else {
                                _this8.data = ret;
                                $('.houseLists').scrollTop(0);
                            }
                        } else {
                            if (isAdd) _this8._query.pageIndex--;else _this8.data = [];
                        }
                    }
                }
                _this8._isLoading = false;
            });

            $.ajax(this.SUBWAY_COLLEGE).then(function (res) {
                if (res) {
                    _this8.generateMarkers(JSON.parse(res));
                }
            });
        }
    }, {
        key: 'generateMarkers',
        value: function generateMarkers(_ref9) {
            var _this9 = this;

            var _ref9$subway = _ref9.subway,
                subway = _ref9$subway === undefined ? [] : _ref9$subway,
                _ref9$university = _ref9.university,
                university = _ref9$university === undefined ? [] : _ref9$university;

            this.removeMarkers();
            var zoomLevel = this._query.zoomLevel;


            if (zoomLevel > 5) return;

            var map = this._map;

            subway.forEach(function (s) {
                var latitude = s.latitude,
                    longitude = s.longitude,
                    station = s.station;

                var content = $('<div class="subwayMarker markerSubway"><i class="subwayIcon peterpan-subway"></i><div>' + station + '\uC5ED</div></div>');
                var custom = new daum.maps.CustomOverlay({
                    position: new daum.maps.LatLng(latitude, longitude),
                    content: content[0]
                });

                (function (latitude, longitude) {
                    content.on('click', function () {
                        _this9.moveTo({ latitude: latitude, longitude: longitude }, 4);
                    });
                })(latitude, longitude);

                custom.setMap(map);
                _this9._markers.push(custom);
            });

            university.forEach(function (u) {
                var latitude = u.latitude,
                    longitude = u.longitude,
                    name = u.name;

                var content = $('<div class="collegeMarker">' + name + '</div>');

                var custom = new daum.maps.CustomOverlay({
                    position: new daum.maps.LatLng(latitude, longitude),
                    content: content[0]
                });

                (function (latitude, longitude) {
                    content.on('click', function () {
                        _this9.moveTo({ latitude: latitude, longitude: longitude }, 4);
                    });
                })(latitude, longitude);

                custom.setMap(map);
                _this9._markers.push(custom);
            });
        }
    }, {
        key: 'removeMarkers',
        value: function removeMarkers() {
            this._markers.forEach(function (m) {
                m.setMap(null);
            });
            this._markers = [];
        }
    }, {
        key: 'setRouteParams',
        value: function setRouteParams(isAdd) {
            window.location.hash = '/?' + this.filters;
            //window.history.pushState(null, document.title, `/?${this.filters}`);
            this.getHouses(isAdd);
        }
    }, {
        key: 'debounce',
        value: function debounce(func, wait, immediate) {
            var timeout;
            return function () {
                var context = this,
                    args = arguments;
                var later = function later() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    }, {
        key: 'scrollTo',
        value: function scrollTo() {
            var index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        }
    }, {
        key: 'setFloater',
        value: function setFloater() {
            var _this10 = this;

            $('.houseLists').on('scroll', function () {
                var containers = $('.housesContainer');
                $('.containerFixed').removeClass('containerFixed');
                var elem = $(_this10);
                var scrolled = $('.houseLists').scrollTop();

                containers.eq(targetIndex).addClass('containerFixed');
                $('.categorySelected').removeClass('categorySelected');
                $('.categoryEach').eq(targetIndex).addClass('categorySelected');
            });
        }
    }, {
        key: 'setScroll',
        value: function setScroll() {
            var _this11 = this;

            var $elem = $('.houseLists');
            var elem = $elem[0];
            $elem.on('wheel', function (event) {
                if (_this11._isLoading) return;
                var originalEvent = event.originalEvent;

                if (originalEvent.deltaY < 0) return;

                var scrollHeight = elem.scrollHeight;
                var elemHeight = $elem.height();
                if (scrollHeight - elemHeight - 10 < $elem.scrollTop()) {
                    _this11.setRouteParams(true);
                }
            });
        }
    }, {
        key: 'generateCategories',
        value: function generateCategories() {
            var _this12 = this;

            var parentElem = $('.categoryWrapper');
            var LEN = this._categories.length + 1;
            var HEIGHT = Math.floor(LEN / 2) * 50;
            $('.houseLists').css('top', 126);

            this._categories.forEach(function (value) {
                var elem = $('<li class="categoryEach">' + value + '</li>');
                parentElem.append(elem);
            });

            $('.categoryWrapper').delegate('li', 'click', function (event) {
                var elem = $(event.currentTarget);
                var value = elem.text();
                if (value === _this12._query.category) return;

                _this12._query.category = value;
                $('.categorySelected').removeClass('categorySelected');
                elem.addClass('categorySelected');
                _this12.setRouteParams();
            });
            this.selectCategoryView();
        }
    }, {
        key: 'selectCategoryView',
        value: function selectCategoryView() {
            var index = this._categories.indexOf(this._query.category);
            var CLASS_NAME = 'categorySelected';
            $('.categorySelected').removeClass(CLASS_NAME);
            $('.categoryEach').eq(index).addClass(CLASS_NAME);
        }
    }, {
        key: 'setInputsValue',
        value: function setInputsValue() {
            var _this13 = this;

            var _query3 = this._query,
                checkDeposit = _query3.checkDeposit,
                checkMonth = _query3.checkMonth,
                isManagerFee = _query3.isManagerFee,
                buildingType = _query3.buildingType,
                contractType = _query3.contractType,
                roomType = _query3.roomType,
                realSize = _query3.realSize,
                additional_options = _query3.additional_options;


            var handler = function () {
                return function (id) {
                    _this13.handleFilter(id, true);
                };
            }();

            $("[name='deposit_1']").val(checkDeposit[0]);
            $("[name='deposit_2']").val(checkDeposit[1]);

            if (!!isManagerFee[0]) handler('addManagerFee');

            $("[name='monthly_1']").val(checkMonth[0]);
            $("[name='monthly_2']").val(checkMonth[1]);

            buildingType.forEach(function (v) {
                switch (v) {
                    case '빌라/주택':
                        handler('villaAndHousing');
                        break;
                    case '오피스텔':
                        handler('officetel');
                        break;
                    case '아파트':
                        handler('apartment');
                        break;
                    case '상가/사무실':
                        handler('storeAndOffice');
                        break;
                }
            });

            contractType.forEach(function (v) {
                switch (v) {
                    case '월세':
                        handler('monthly');
                        break;
                    case '전세':
                        handler('rental');
                        break;
                    case '매매':
                        handler('sale');
                        break;
                    case '단기임대':
                        handler('shortTerm');
                        break;
                }
            });

            roomType.forEach(function (v) {
                switch (v) {
                    case '원룸':
                        handler('oneRoom');
                        break;
                    case '투룸':
                        handler('twoRoom');
                        break;
                    case '쓰리룸 이상':
                        handler('threeRoom');
                        break;
                }
            });

            realSize.forEach(function (v) {
                switch (v) {
                    case '5평 이하':
                        handler('is_5');
                        break;
                    case '5평 ~ 10평':
                        handler('is_5_10');
                        break;
                    case '10평 이상':
                        handler('is_10');
                        break;
                }
            });

            additional_options.forEach(function (v) {
                switch (v) {
                    case '신축':
                        handler('isNewVila');
                        break;
                    case '풀옵션':
                        handler('isfullOption');
                        break;
                    case '주차가능':
                        handler('isParking');
                        break;
                    case '엘리베이터':
                        handler('isElevator');
                        break;
                    case '반려동물':
                        handler('isPet');
                        break;
                    case '전세자금대출':
                        handler('isRon');
                        break;
                }
            });
        }
    }, {
        key: 'data',
        get: function get() {
            return this._data;
        },
        set: function set(data) {
            this._data = data;
            $('.ui-tooltip').remove();
            this.renderList();
            if (this._renderType === 'overlay' || this._renderType === 'overlay_twin') {
                this.setHouseMarkers();
            } else {
                this.setClusters();
            }
            this.hideActiveMarker();
        }
    }, {
        key: 'filters',
        get: function get() {
            var info = this.getMapInfo();
            //let filters = `filter=latitude:${info.ja}~${info.ia}||longitude:${info.ca}~${info.ha}`;
            var filters = 'latitude:' + info.ja + '~' + info.ia + '||longitude:' + info.ca + '~' + info.ha;
            for (var key in this._query) {
                filters += this.convert(key, this._query[key]);
            }

            var zoomLevel = this._query.zoomLevel;


            filters += '&zoomLevel=' + zoomLevel;

            var _map$getCenter = this._map.getCenter(),
                jb = _map$getCenter.jb,
                ib = _map$getCenter.ib;

            filters += '&center={"latitude": ' + jb + ', "longitude": ' + ib + '}';
            filters += '&pageSize=' + this._query.pageSize;
            filters += '&page=' + this._query.pageIndex;
            filters += '&type=' + this._query.category;

            return 'filter=' + encodeURI(filters);
        }
    }, {
        key: 'API_URL_GET_HOUSES',
        get: function get() {
            //let query = `https://api.peterpanz.com/houses/area?${this.filters}`;
            return 'http://api.seoul.peterpanz.com/list?' + this.filters;
        }
    }, {
        key: 'activeDom',
        get: function get() {
            return this._activeDom;
        },
        set: function set(dom) {
            if (this._activeDom) $(this._activeDom).removeClass('activeCluster');
            if (dom) $(dom).addClass('activeCluster');
            this._activeDom = dom;
        }
    }, {
        key: 'SUBWAY_COLLEGE',
        get: function get() {
            var _getMapInfo = this.getMapInfo(),
                ja = _getMapInfo.ja,
                ia = _getMapInfo.ia,
                ca = _getMapInfo.ca,
                ha = _getMapInfo.ha;

            return 'http://api.seoul.peterpanz.com/info?filter=latitude:' + ja + '~' + ia + '||longitude:' + ca + '~' + ha;
        }
    }]);

    return PeterpanMap;
}();
'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PeterpanPrice = function () {
    function PeterpanPrice() {
        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        _classCallCheck(this, PeterpanPrice);

        this._map = null;
        this._types = [];
        this._selectedTypes = {};
        this._data = null;
        this._markers = [];
        this.getData = this.debounce(this.getData, 250);
    }

    _createClass(PeterpanPrice, [{
        key: 'init',
        value: function init() {
            this.generateMap();
            this.getTypes();
            return this;
        }
    }, {
        key: 'getTypes',
        value: function getTypes() {
            var _this = this;

            $.ajax('http://api.seoul.peterpanz.com/price/type').then(function (res) {
                if (res) {
                    _this._types = JSON.parse(res);
                    _this._types.forEach(function (t) {
                        _this.setSelectedTypes(t.type[0], t.value[0]);
                    });
                    _this.generateCheckBox();
                    _this.getData();
                }
            });
        }
    }, {
        key: 'debounce',
        value: function debounce(func, wait, immediate) {
            var timeout;
            return function () {
                var context = this,
                    args = arguments;
                var later = function later() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    }, {
        key: 'setSelectedTypes',
        value: function setSelectedTypes(key, type) {
            this._selectedTypes[key] = type;
            this.getData();
        }
    }, {
        key: 'generateCheckBox',
        value: function generateCheckBox() {
            var _this2 = this;

            var TEMPLATE = '\n            <div class="radioContainer">\n                ' + getBoxes(this._types, this._selectedTypes) + '\n            </div>\n        ';

            function getBoxes(types, selected) {
                var ret = '';
                _.forEach(types, function (t) {
                    ret += '<div class="box">';
                    t.value.forEach(function (v) {
                        var str = '\n' +
                            '<div><label>\n' +
                            '    ' + v + '\n' +
                            '    <input type="radio" name="' + t.type[0] + '" value="' + v + '" ' + (selected[t.type[0]] === v ? 'checked' : '') + ' />\n' +
                            '</label></div>\n                    ';
                        ret += str;
                    });
                    ret += '</div>';
                });
                return ret;
            }

            $('#priceContainer').prepend($(TEMPLATE));
            $('.radioContainer input').on('change', function (_ref) {
                var target = _ref.target;

                _this2.setSelectedTypes($(target).attr('name'), target.value);
            });
        }
    }, {
        key: 'getData',
        value: function getData() {
            var _this3 = this;

            var _getMapInfo = this.getMapInfo(),
                ja = _getMapInfo.ja,
                ia = _getMapInfo.ia,
                ca = _getMapInfo.ca,
                ha = _getMapInfo.ha;

            var ret = {};

            var URL = 'http://api.seoul.peterpanz.com/price?filter=latitude:' + ja + '~' + ia + '||longitude:' + ca + '~' + ha + '&zoomLevel=' + this._map.getLevel();

            _.forEach(this._selectedTypes, function (value, key) {
                URL += '&' + key + '=' + value;
            });

            $.ajax(URL).then(function (res) {
                if (res) {
                    res = JSON.parse(res);
                    _this3.data = res.data;
                }
            });
        }
    }, {
        key: 'generateMap',
        value: function generateMap() {
            var _this4 = this;

            var container = document.getElementById('map');
            var options = {
                center: new daum.maps.LatLng(37.5667295445299, 126.97835799073495),
                level: 4
            };

            var map = new daum.maps.Map(container, options);
            daum.maps.event.addListener(map, 'bounds_changed', function () {
                _this4.getData();
            });
            this._map = map;
        }
    }, {
        key: 'generateMarkers',
        value: function generateMarkers() {
            var _this5 = this;

            this.removeMarkers();
            var map = this._map;
            var data = this._data;
            var zIndex = 0;

            data.forEach(function (_ref2) {
                var html = _ref2.html,
                    location = _ref2.location;
                var latitude = location.latitude,
                    longitude = location.longitude;


                html = $(html);
                var custom = new daum.maps.CustomOverlay({
                    position: new daum.maps.LatLng(Number(latitude), Number(longitude)),
                    content: html[0]
                });
                html.on('mouseover', function () {
                    custom.setZIndex(zIndex++);
                });


                custom.setMap(map);
                _this5._markers.push(custom);
                (function (lat, lon) {
                    html.find('h3').on('click', function () {
                        window.open('/house/search#/?filter=&center={"latitude": ' + lat + ', "longitude": ' + lon + '}&zoomLevel=6', '_blank');
                    });
                })(latitude, longitude);
            });

            return;
            var arr = [];
            for (var firstKey in data) {
                for (var secondKey in data[firstKey]) {
                    for (var thirdKey in data[firstKey][secondKey]) {
                        var obj = {
                            siName: firstKey,
                            guName: secondKey,
                            dongName: thirdKey
                        };
                        Object.assign(obj, data[firstKey][secondKey][thirdKey]);
                        arr.push(obj);
                    }
                }
            }

            arr.forEach(function (v) {
                var siName = v.siName,
                    guName = v.guName,
                    dongName = v.dongName,
                    price = v.price,
                    location = v.location;
                var latitude = location.latitude,
                    longitude = location.longitude;


                var custom = new daum.maps.CustomOverlay({
                    position: new daum.maps.LatLng(Number(latitude), Number(longitude)),
                    content: '\n                    <div class="priceMarker">\n                        <div class="title">\n                            ' + siName + '<br/>\n                            ' + guName + '/' + dongName + '\n                        </div>\n                        ' + getContent(_this5._rentType, price) + '\n                    </div>\n                '
                });

                custom.setMap(map);
                _this5._markers.push(custom);
            });

            function getContent(type, price) {
                var util = Peterpan.Utils;
                if (type === '전세') {
                    var _price$deposit = price.deposit,
                        max = _price$deposit.max,
                        min = _price$deposit.min,
                        median = _price$deposit.median;

                    return '\n                    <div class="sell">\n                        <div class="top">\n                            <div class="type">\uC804\uC138(\uBCF4\uC99D\uAE08)</div>\n                        </div>\n                        <div>\n                            <span>\uC2DC\uC138</span>\n                            <span>' + util.convertMoney(median) + '</span>\n                            <span>\uB9CC</span>\n                        </div>\n                        <div>\n                            <span>\uCD5C\uC800</span>\n                            <span>' + util.convertMoney(min) + '</span>\n                            <span>\uB9CC</span>\n                        </div>\n                        <div>\n                            <span>\uCD5C\uACE0</span>\n                            <span>' + util.convertMoney(max) + '</span>\n                            <span>\uB9CC</span>\n                        </div>\n                    </div>\n                ';
                } else {
                    var deposit = price.deposit,
                        monthly_fee = price.monthly_fee;
                    var _max = deposit.max,
                        _min = deposit.min,
                        _median = deposit.median;
                    var monthly_max = monthly_fee.max,
                        monthly_min = monthly_fee.min,
                        monthly_median = monthly_fee.median;

                    return '\n                    <div class="sell">\n                        <div class="top">\n                            <div class="type">\uC6D4\uC138(\uBCF4\uC99D\uAE08/\uC6D4\uC138)</div>\n                        </div>\n                        <div>\n                            <span>\uC2DC\uC138</span>\n                            <span>' + util.convertMoney(_median) + '/' + util.convertMoney(monthly_median) + '</span>\n                            <span>\uB9CC</span>\n                        </div>\n                    </div>\n                ';
                }
            }
        }
    }, {
        key: 'removeMarkers',
        value: function removeMarkers() {
            this._markers.forEach(function (m) {
                m.setMap(null);
            });
            this._markers = [];
        }
    }, {
        key: 'getMapInfo',
        value: function getMapInfo() {
            var _map$getBounds = this._map.getBounds(),
                ja = _map$getBounds.ja,
                ia = _map$getBounds.ia,
                ha = _map$getBounds.ha,
                ca = _map$getBounds.ca;

            return {
                ja: Number(ja).toFixed(7),
                ia: Number(ia).toFixed(7),
                ca: Number(ca).toFixed(7),
                ha: Number(ha).toFixed(7)
            };
        }
    }, {
        key: 'data',
        set: function set(data) {
            this._data = data;
            this.generateMarkers();
        }
    }]);

    return PeterpanPrice;
}();
'use strict';

window.Peterpan = window.Peterpan || {};

Peterpan.Utils = {
    convertMoney: function convertMoney(value) {
        value = Number(value);
        var str = '';
        value = String(value / 10000);
        if (value.length > 4) {
            var LEN = value.length - 4;
            var head = value.substring(0, LEN);
            var tail = value.substring(LEN);
            tail = Number(tail) || '';
            str = head + '\uC5B5' + tail;
        } else str = value;
        return str;
    },
    getHash: function getHash() {
        return Math.random().toString(36).substring(7);
    }
};
