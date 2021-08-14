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
define("CHANNEL_ID", "-1001378347176");
// End HardCoded Env :)

function proccess($_link)
{
    $mp3_file = Youtube::download($_link);

    foreach ($mp3_file as $file) {
        $message = $file["title"] . "\n\n";
        $message .= $_link . "\n\n";
        $message .= "@ArabicMadih" . "\n";

        Telegram::send_file(CHANNEL_ID, $file["mp3"], $message, str_replace("mp3", "jpg", $file["mp3"]));
    }
}

($myfile = fopen(__DIR__ . "/files/offset.txt", "r")) or die("Unable to open file!");
$offset = fread($myfile, filesize(__DIR__ . "/files/offset.txt"));
fclose($myfile);
$offset = intval($offset);
$updates = Telegram::getUpdates($offset);
$here = false;
foreach ($updates["result"] as $update) {
    $here = true;
    $offset = $update["update_id"];
    if (!in_array($update["message"]["from"]["id"], ADMINS_ID)) {
        Telegram::forward(ADMINS_ID[1], $update["message"]["from"]["id"], $update["message"]["message_id"]);
        Telegram::send_message(
            "ADMIN WILL RECIVE YOUR MESSAGE, THANK YOU FOR CONTRIBUTE.",
            $update["message"]["from"]["id"]
        );
        return;
    }
    if (filter_var($update["message"]["text"], FILTER_VALIDATE_URL)) {
        // var_dump();
        Telegram::send_message("IN PROCCESS", $update["message"]["from"]["id"]);
        try {
            proccess($update["message"]["text"]);
        } catch (\Throwable $th) {
            echo "Some Error \n";
        }
        return;
    }
    Telegram::send_message("UNDEFINE MESSAGE. JUST LINK", $update["message"]["from"]["id"]);
    // die();
}
if ($here) {
    $offset++;
}
($myfile = fopen(__DIR__ . "/files/offset.txt", "w")) or die("Unable to open file!");
fwrite($myfile, (string) $offset);
fclose($myfile);
