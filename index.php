<?php
declare(strict_types=1);

require "./vendor/autoload.php";

use Lib\Youtube;
use Lib\Telegram;
// Change it if you are here :)
define("PYTHON_PATH", "/usr/bin/python3");
define("APP_BIN", __DIR__ . "/bin");
define("DOWNLOAD_PATH", __DIR__ . "/files");
define("ADMINS_ID", ["1069225", "91416644"]);
define("‌TOKEN", "TOKEN HERE");
// define("CHANNEL_ID", "-1001378347176");
define("CHANNEL_ID", "-1001144200514");
// End HardCoded Env :)

// $mp3_file = Youtube::download("https://www.youtube.com/watch?v=FtCi7xT9Le4");
// var_dump($mp3_file);
$rs = Telegram::send_photo(
    "91416644",
    "/Users/office/public_html/ArabicMadihTg/files/1443-عينٌ بكت _ حسين فيصل _ محرم 1443.jpg",
    "BOT TEST"
);
// $rs = Telegram::send_message("hell", "91416644");

var_dump($rs);
// foreach ($mp3_file as $file) {
// }