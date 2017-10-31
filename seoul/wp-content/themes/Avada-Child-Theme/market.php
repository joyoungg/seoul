<?php
/**
 * Template name: market admin view
 */

?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>시세정보 어드민</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form action="http://seoul.peterpanz.com/update" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="price" id="files" multiple/>
                    <button>전송</button>
                </div>
            </form>

            <form class="form-inline">
                <div class="form-group">
                    <label class="sr-only" for="year"></label>
                    <select id="year" class="form-control">
                        <?php for ($i = 0; $i < 3; $i++) : ?>
                            <?php $year = intval(date('Y')) - 1 + intval($i); ?>
                            <option value="<?= $year ?>" <?php if ($year === intval(date('Y'))) echo "selected"; ?>><?= $year ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="month"></label>
                    <select id="month" class="form-control">
                        <?php for ($i = 1; $i < 13; $i++) : ?>
                            <option value="<?= $i ?>" <?php if ($i === intval(date('m'))) echo "selected"; ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="house_type"></label>
                    <select id="house_type" class="form-control">
                        <option value="단독다가구">단독다가구</option>
                        <option value="아파트">아파트</option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="room_type"></label>
                    <select id="room_type" class="form-control">
                        <option value="35이하">~ 35m<sup>2</sup></option>
                        <option value="50이하">~ 50m<sup>2</sup></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="rent_type"></label>
                    <select id="rent_type" class="form-control">
                        <option value="월세">월세</option>
                        <option value="전세">전세</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success" id="search">조회</button>
            </form>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                <tr>
                    <th>-</th>
                    <th>주택유형</th>
                    <th>면적구분</th>
                    <th>전월세</th>
                    <th>자치구</th>
                    <th>법정동</th>
                    <th>보증금</th>
                    <th>월세</th>
                    <th>날짜</th>
                </tr>
                </thead>
                <tbody id="content">
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    $(function () {
        $("#search").click(function (event) {
            event.preventDefault();
            $.get(
                "http://api.seoul.peterpanz.com/price/market/search",
                {
                    year: $("#year option:selected").val(),
                    month: $("#month option:selected").val(),
                    houseType: $("#house_type option:selected").val(),
                    roomType: $("#room_type option:selected").val(),
                    rentType: $("#rent_type option:selected").val()
                },
                function (data) {
                    var html = '';
                    data = JSON.parse(data);
                    for (var key in data) {
                        html += '<tr>';
                        html += '<td>' + data[key]['idx'] + '</td>';
                        html += '<td>' + data[key]['house_type'] + '</td>';
                        html += '<td>' + data[key]['room_type'] + '</td>';
                        html += '<td>' + data[key]['rent_type'] + '</td>';
                        html += '<td>' + data[key]['gugun'] + '</td>';
                        html += '<td>' + data[key]['dong'] + '</td>';
                        html += '<td>' + data[key]['deposit'] + '</td>';
                        html += '<td>' + data[key]['monthly_fee'] + '</td>';
                        html += '<td>' + data[key]['date'] + '</td>';
                        html += '</tr>';
                    }
                    $('#content').html(html);

                }
            );
        });
    });

    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

        // files is a FileList of File objects. List some properties.
        var output = [];
        debugger;
        for (var i = 0, f; f = files[i]; i++) {
            output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                f.size, ' bytes, last modified: ',
                f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                '</li>');
        }
        document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
    }

    document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>

</body>
</html>
