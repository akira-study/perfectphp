<?php 

// データベースに接続
$link = mysqli_connect('localhost', 'root', 'pc19930831A', 'oneline_bbs') or 
die('データベースに接続できません：' . mysql_error());

// データベースを選択する
mysqli_select_db($link, 'oneline_bbs');

$errors = array();

// POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 名前が正しく入力されているかチェック
    $name = null; // 初期化
    if (!isset($_POST['name']) || !strlen($_POST['name'])) { // 名前が入力されていなかったら
        $errors['name'] = '名前を入力してください';
    } else if (strlen($_POST['name']) > 40) { // 入力された名前の文字数が40字より大きかったら
        $errors['name'] = '名前は40文字以内で入力してください';
    } else { // 入力された名前を$nameへ格納
        $name = $_POST;
    }

    // ひとことが正しく入力されているかチェック
    $comment = null; // 初期化
    if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
        $errors['comment'] = 'ひとことを入力してください';
    } else if (strlen($_POST['comment']) > 200) {
        $errors['comment'] = 'ひとことは200文字以内で入力してください';
    } else {
        $comment = $_POST['comment'];
    }
    
    // エラーがなければ保存
    if (count($errors) === 0) {
        // 保存するためのSQL文を作成
        $sql = "insert into post
                (name, comment, created) 
                values
                (
                 '" . mysql_real_escape_string($name) . "',
                 '" . mysql_real_escape_string($comment) . "',
                 '" . date('Y-m-d H:i:s') . "'
                )";
        // 保存する
        mysqli_query($link, $sql);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http:/wwww.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
    
    <form action="bbs.php" method="post">
        名前：<input type="text" name="name" /><br />
        ひとこと：<input type="text" name="comment" size="60" /><br />
        <input type="submit" name="submit" value="送信" />
    </form>
</body>
</html>

