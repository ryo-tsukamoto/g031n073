<?php
//データベースに接続
$db_user = 'root';         // ユーザー名
$db_pass = '4caozmy7';     // パスワード
$db_name = 'bbs';          // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
//mysqlの接続エラー処理
if ($mysqli->connect_errno) {
  printf("Connect failed: %s\n", $mysqli->connect_errno);
  exit();
}

// 削除ボタンが押された時
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['bbs_del'])) {    //passwordとidが一致の場合delete
    $delete = $mysqli->query("DELETE FROM `messages` WHERE id = {$_POST['id']} AND password = '{$_POST['password']}'");
    $delete_count = $mysqli->affected_rows;   //deleteの件数を取得
    if ($delete_count == 1) {   //削除件数が１件の時
      print '<script>
              alert("削除しました．");
              location.href = "./bbs.php";
            </script>';
    } elseif ($delete_count == 0) {   //パスワードが違う時エラーとを表示
      print '<script>
              alert("パスワードが違います");
              location.href = "./bbs.php";
            </script>';
    } else {    //それ以外の場合のエラー処理
      printf("Connect failed: %s\n", $mysqli->connect_errno);
      exit();
    }
  }

    //　更新ボタンが押された時　更新するコメントのレコードを読み込む
    if (isset($_POST['bbs_ope'])) {    //更新ボタンが押された時
      $result = $mysqli->query("SELECT * FROM messages WHERE id = {$_POST['id']} AND password = '{$_POST['password']}'");
      $result_count = $mysqli->affected_rows;   //selectの件数を取得
      if ($result_count == 0) {   //パスワードが違う時エラーとを表示
        print '<script>
                alert("パスワードが違います");
                location.href = "./bbs.php";
              </script>';
      } elseif ($result_count == -1) {    //それ以外のエラー処理
        printf("Query failed: %s\n", $mysqli->error);
        exit();
      }
    }

    //フォームの値を受け取りmysqlを更新する
    if (!empty($_POST['comment'])) {   //commentの値が空値でない時
      $update = $mysqli->query("UPDATE `messages` SET `body`='{$_POST['comment']}' WHERE id= {$_POST['id']} and password = '{$_POST['password']}'");
      $update_count = $mysqli->affected_rows;   //更新件数の取得
      if ($update_count == 1) {   //更新できた時
        print '<script>
                alert("更新しました．");
                location.href = "./bbs.php";
              </script>';
      } else {    //それ以外のエラー処理
        printf("Query failed: %s\n", $mysqli->error);
        exit();
      }
    }
}

//接続を閉じる
$mysqli->close();

?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>掲示板</title>
  </head>

  <body>
    <div class="container">
      <div class="page-header">
        <h1>投稿内容の編集</h1>
      </div>

      <table class="table table-striped">
        <thead>
          <tr>
            <th style="width:15%;">Name</th>
            <th style="width:30%;">Comment</th>
            <th>更新</th>
          </tr>
        </thead>

        <?php foreach ($result as $row):  //テーブルの内容を表示する?>
          <form name="edit" action="" method="post">
            <tbody>
              <tr>
                <td><?= $row['name'] ?></td>
                <td><input type="text" name="comment" class="form-control" value="<?= $row['body'] ?>"></td>
                <td>
                  <input type="hidden" name="password" value="<?= $_POST['password'] ?>">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="submit" value="更新" class="btn btn-danger" onclick="check()">
                </td>
              </tr>
            </tbody>
          </form>
        <?php endforeach; ?>
      </table>
    </div>

    <!-- 文字入力がない場合の警告ポップアップ -->
    <script language="JavaScript">
    function check() {
        if(document.edit.comment.value == "") {   //formのcommentの値が空欄だった場合
          alert("Commentを記入してください.");    //alertを表示する
          return ;
        }
    }
    </script>
  </body>
</html>
