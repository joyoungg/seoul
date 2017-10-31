"use strict";

$(document).ready(function () {
    var peterpanMap = new Peterpan();
    peterpanMap.init();

    window.bully = peterpanMap;
});
'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Peterpan = function () {
    function Peterpan() {
        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        _classCallCheck(this, Peterpan);

        this._map = null;
        this._clusterer = null;
        this._data = null;
        this._activeDom = null;
        this._zoomLevel = undefined;
        this._activeMarker = null;

        this.getHouses = this.debounce(this.getHouses, 250);
        this.handleComplete = this.debounce(this.handleComplete, 200);
        this.setFilterToggle = this._setFilterToggle();

        this.setScroller();
        this.setFloater();
        this.setAutoComplete();
        this.setFilterToggle();
    }

    _createClass(Peterpan, [{
        key: 'hideActiveMarker',
        value: function hideActiveMarker() {
            this._activeMarker.setPosition(new daum.maps.LatLng(0, 0));
        }
    }, {
        key: 'init',
        value: function init() {
            var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

            this.generateMap(options.id);
            this.generateClusterer();

            this.getHouses();
        }
    }, {
        key: 'generateMap',
        value: function generateMap() {
            var id = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'map';

            var that = this;
            var map = new daum.maps.Map(document.getElementById(id), {
                center: new daum.maps.LatLng(37.5105634, 127.0227266), //지도의 중심좌표.
                level: 3
            });
            this._map = map;
            var event = daum.maps.event;


            var customOverlay = new daum.maps.CustomOverlay({
                position: new daum.maps.LatLng(0, 0),
                content: '<div class="activeMarker"></div>'
            });

            customOverlay.setMap(map);
            this._activeMarker = customOverlay;

            daum.maps.event.addListener(map, 'bounds_changed', function () {
                that.getHouses();
            });

            return map;
        }
    }, {
        key: 'generateClusterer',
        value: function generateClusterer() {
            var _this = this;

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
                _this.activeDom = cluster._content;
                var markers = cluster._markers.map(function (m) {
                    return m.datum;
                });
                _this.renderList(markers);
            });

            event.addListener(clusterer, 'clusterover', function (_ref) {
                var _content = _ref._content;

                $(_content).addClass('activeCluster');
            });

            event.addListener(clusterer, 'clusterout', function (_ref2) {
                var _content = _ref2._content;

                if (_this.activeDom !== _content) $(_content).removeClass('activeCluster');
            });

            this._clusterer = clusterer;
        }
    }, {
        key: 'resetClusters',
        value: function resetClusters() {
            if (this._clusterer) this._clusterer.clear();
        }
    }, {
        key: 'setClusters',
        value: function setClusters() {
            this.resetClusters();

            var that = this;

            var _data = this.data,
                withoutFee = _data.withoutFee,
                agency = _data.agency;

            var list = [].concat(_toConsumableArray(withoutFee.image), _toConsumableArray(agency.image));

            var markers = list.map(function (h) {
                var location = h.location;
                var _location$coordinate = location.coordinate,
                    latitude = _location$coordinate.latitude,
                    longitude = _location$coordinate.longitude;

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
            $('.houseEach').remove();
        }
    }, {
        key: 'renderList',
        value: function renderList(list) {
            this.resetList();
            debugger;

            var template = '\n            ' +
                '<div class="houseEach">\n' +
                '    <a href="">\n' +
                '        <div class="houseImage" style="background-image: url(\'%IMAGE_PATH\')" />\n' +
                '        <div class="houseContentContainer">\n' +
                '            <div class="houseContractType" data-contract-type=%CONTRACT_TYPE>%CONTRACT_TYPE</div>\n' +
                '            <div class="housePrice">%HOUSE_PRICE</div>\n' +
                '            <div class="houseDesc">%HOUSE_DESC</div>\n' +
                '            <div class="houseType">%HOUSE_TYPE</div>\n' +
                '            <div class="houseBadge"></div>' +
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
                    generateView(v, parentNode);
                });
            } else {
                var categoryData = this.data;
                var withoutFee = categoryData.withoutFee,
                    agency = categoryData.agency;

                if (withoutFee) {
                    parentNode = $('.housesContent.first').eq(0);
                    withoutFee.image.forEach(function (v) {
                        generateView(v, parentNode);
                    });
                }
                if (agency) {
                    parentNode = $('.housesContent.second').eq(0);
                    agency.image.forEach(function (v) {
                        generateView(v, parentNode);
                    });
                }
            }

            var that = this;

            function generateView(house) {
                var parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : parentNode;
                var info = house.info,
                    type = house.type,
                    price = house.price;

                var contractType = type.contract_type;
                var convertedTemplate = template.replace('%IMAGE_PATH', info.thumbnail).replace('%HOUSE_PRICE', function () {
                    var deposit = price.deposit,
                        monthly_fee = price.monthly_fee;

                    var str = '';
                    deposit = String(deposit / 10000);
                    if (deposit.length > 4) {
                        var LEN = deposit.length - 4;
                        var head = deposit.substring(0, LEN);
                        var tail = deposit.substring(LEN);
                        tail = Number(tail) || '';
                        str = head + '\uC5B5' + tail;
                    } else str = deposit;
                    if (monthly_fee) str = str + '/' + monthly_fee / 10000;
                    return str;
                }).replace('%HOUSE_DESC', info.subject).replace('%HOUSE_TYPE', function () {
                    return '\n                                ' + type.building_type + ' | ' + (ROOM_MAP[type.building_form] || '방 1개') + '\n                                | \uAD00\uB9AC\uBE44 ' + price.maintenance_cost / 10000 + '\uB9CC\uC6D0\n                                | ' + house.floor.target + '\uCE35\n                            ';
                }()).replace(/%CONTRACT_TYPE/gi, type.contract_type);

                var dom = $(convertedTemplate);
                (function (h) {
                    dom.mouseenter(function () {
                        var pos = h.location.coordinate;
                        pos = new daum.maps.LatLng(pos.latitude, pos.longitude);
                        that._activeMarker.setPosition(pos);
                        that._activeMarker.setZIndex(9999);
                    });

                    dom.mouseleave(function () {
                        that.hideActiveMarker();
                    });
                })(house);
                parent.append(dom);
            }
            setTimeout(function () {
                that.scrollTo(0);
            }, 300);
        }
    }, {
        key: 'getMapInfo',
        value: function getMapInfo() {
            return this._map.getBounds();
        }
    }, {
        key: 'getAutoCompleteSource',
        value: function getAutoCompleteSource() {
            function getRand() {
                return Math.random().toString(36).substring(7);
            }
            return [{
                value: "jquery",
                label: "jQuery",
                desc: "the write less, do more, JavaScript library",
                icon: "location",
                id: getRand()

            }, {
                value: "jquery-ui",
                label: "jQuery UI",
                desc: "the official user interface library for jQuery",
                icon: "college",
                id: getRand()
            }, {
                value: "sizzlejs",
                label: "Sizzle JS",
                desc: "a pure-JavaScript CSS selector engine",
                icon: "subway",
                id: getRand()
            }];
        }
    }, {
        key: 'setAutoComplete',
        value: function setAutoComplete() {
            var _this2 = this;

            var dom = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $('#searchInput');

            var elem = dom.autocomplete({
                minLength: 2,
                classes: {
                    'ui-autocomplete': 'ui-autocomplete autoCompleteContainer'
                },
                source: function source(req, res) {
                    res(_this2.getAutoCompleteSource());
                },
                focus: function focus(event, ui) {
                    $('.autoEachFocused').removeClass('autoEachFocused');
                    $('#' + ui.item.id).addClass('autoEachFocused');
                },
                select: function select(event, ui) {
                    _this2.moveTo();
                }
            });

            elem.autocomplete("instance")._renderItem = function (ul, item) {
                return $('<li class=\'autoEach\' id=\'' + item.id + '\'>').append("<span>" + item.label + "" + item.desc + "</span>" + ('<i class=\'peterpan-' + item.icon + '\'></i>')).appendTo(ul);
            };

            elem.autocomplete("instance")._resizeMenu = function () {
                this.menu.element.outerWidth(dom.width());
            };
        }
    }, {
        key: '_setFilterToggle',
        value: function _setFilterToggle() {
            var _visible = false;
            var elem = $('.filterHeader');
            var targetElem = $('.filterBody');
            var closeElem = $('.filterCloseButton');

            return function () {
                elem.on('click', function () {
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
                    } else {
                        targetElem.addClass('peterpanRemove');
                        elem.removeClass('toggleTrue');
                        elem.addClass('toggleFalse');
                    }
                }
            };
        }
    }, {
        key: 'moveTo',
        value: function moveTo(house) {
            //TODO real datum needed
            house = this._temp;
            var _house = house,
                _house$location$coord = _house.location.coordinate,
                latitude = _house$location$coord.latitude,
                longitude = _house$location$coord.longitude;

            var LATLNG = new daum.maps.LatLng(latitude, longitude);
            this._map.panTo(LATLNG);
        }
    }, {
        key: 'getHouses',
        value: function getHouses() {
            var _this3 = this;

            $.ajax(this.API_URL_GET_HOUSES).then(function (res) {
                if (res) _this3.data = res.houses;
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
        key: 'scrollTo',
        value: function scrollTo() {
            var index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

            $('.containerFixed').removeClass('containerFixed');
            var pos = 0;

            var container = $('.housesContainer').eq(index);
            container.addClass('containerFixed');
            var indicator = $('.housesIndicator').eq(index);
            pos = container.position().top;
            var parent = $('.houseLists').eq(0);
            parent.animate({ scrollTop: pos || 1 }, 150);
        }
    }, {
        key: 'setFloater',
        value: function setFloater() {
            var _this4 = this;

            $('.houseLists').on('scroll', function () {
                var containers = $('.housesContainer');
                $('.containerFixed').removeClass('containerFixed');
                var elem = $(_this4);
                var scrolled = $('.houseLists').scrollTop();
                var targetIndex = scrolled + 10 > containers.eq(1).position().top ? 1 : 0;
                containers.eq(targetIndex).addClass('containerFixed');
                $('.categorySelected').removeClass('categorySelected');
                $('.categoryEach').eq(targetIndex).addClass('categorySelected');
            });
        }
    }, {
        key: 'setScroller',
        value: function setScroller() {
            var _this5 = this;

            $('.categoryWrapper').delegate('li', 'click', function (event) {
                var elem = $(event.currentTarget);
                _this5.scrollTo(Number(elem.attr('selector')));
            });
        }
    }, {
        key: 'data',
        get: function get() {
            return this._data;
        },
        set: function set(data) {
            this._data = data;
            this.renderList();
            this.setClusters();
            this.hideActiveMarker();
        }
    }, {
        key: 'API_URL_GET_HOUSES',
        get: function get() {
            var info = this.getMapInfo();
            for (var key in info) {
                if (info.hasOwnProperty(key)) info[key] = Number(info[key]).toFixed(7);
            }
            return 'https://api.peterpanz.com/houses/area?filter=latitude:' + info.ha + '~' + info.ga + '%7C%7Clongitude:' + info.ba + '~' + info.fa + '&zoomLevel=11&center=%7B%22y%22:37.514236,%22_lat%22:37.514236,%22x%22:127.031593,%22_lng%22:127.031593%7D&pageSize=90&pageIndex=1';
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
    }]);

    return Peterpan;
}();
