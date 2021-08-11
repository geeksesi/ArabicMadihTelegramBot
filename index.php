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
define("‌TOKEN", "956711743:AAFFdGFskUekAmNnyUrbH2gEUu22Fs0w_xc");
// define("CHANNEL_ID", "-1001378347176");
// define("CHANNEL_ID", "-1001144200514");
// End HardCoded Env :)

// $mp3_file = Youtube::download("https://www.youtube.com/watch?v=FtCi7xT9Le4");
// var_dump($mp3_file);
$rs = new Telegram;

$rs->send_file_request(
    476080724,
    "/home/smtz/Projects/Zanjani-022.png",
    "BOT TEST"
);
// $rs = Telegram::send_message("hell", "476080724");

var_dump($rs);
// foreach ($mp3_file as $file) {
// }
