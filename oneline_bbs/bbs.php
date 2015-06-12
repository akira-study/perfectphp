<?php 

// データベースに接続
$link = mysqli_connect('localhost', 'root', 'pc19930831A', 'oneline_bbs') or 
die('データベースに接続できません：' . mysqli_error($link));

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
                 '" . mysqli_real_escape_string($link, $name) . "',
                 '" . mysqli_real_escape_string($link, $comment) . "',
                 '" . date('Y-m-d H:i:s') . "'
                )";
        // 保存する
        mysqli_query($link, $sql);

        mysqli_close($link);

        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
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
        <?php if (count($errors)): ?>
        <ul class="error_list">
            <?php foreach ($errors as $error): ?>
            <li>
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        名前：<input type="text" name="name" /><br />
        ひとこと：<input type="text" name="comment" size="60" /><br />
        <input type="submit" name="submit" value="送信" />
    </form>

    <?php 
    // 投稿された内容を取得するSQL文を作成して結果を取得
    $sql = "select * from post order by created desc";
    $result = mysqli_query($link, $sql);
    ?>
    
    <?php if ($result !== false && mysqli_num_rows($result)): ?>
    <ul>
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
        <li>
            <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?> 
            <?php echo htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8'); ?>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php endif; ?>

    <?php 
    // 取得結果を解放して接続を閉じる
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>
</html>

