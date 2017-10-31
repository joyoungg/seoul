<?php
/**
 * Template name: market update admin view
 */

echo "confirm file information <br />";
$uploadfile = $_FILES['price'] ['name'];
if (move_uploaded_file($_FILES['price']['tmp_name'], $uploadfile)) {
    echo "파일이 업로드 되었습니다.<br />";
    echo $_FILES['price']['name'];
    echo "1. file name : {$_FILES['price']['name']}<br />";
    echo "2. file type : {$_FILES['price']['type']}<br />";
    echo "3. file size : {$_FILES['price']['size']} byte <br />";
    echo "4. temporary file name : {$_FILES['price']['size']}<br />";
} else {
    echo "파일 업로드 실패 !! 다시 시도해주세요.<br />";
}

?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>시세정보 올리기</title>
</head>
<body>
<pre>
<?php
print_r($GLOBALS);
?>
</pre>

</body>
</html>
