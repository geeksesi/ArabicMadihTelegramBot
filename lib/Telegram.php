<?php
namespace Lib;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Telegram
{
    /**
     * [$api_url description]
     *
     * @var string
     */
    private static string $api_url;
    /**
     * [$guzzle_client description]
     *
     * @var \GuzzleHttp\Client
     */
    private static Client $guzzle_client;
    /**
     * [$error description]
     *
     * @var array
     */
    private static array $error;

    /**
     * [init description]
     *
     * @return  void  [return description]
     */
    protected static function init()
    {
        if (isset(self::$api_url)) {
            return;
        }
        self::$api_url = "https://api.telegram.org/bot" . ‌TOKEN . "/";
        self::$guzzle_client = new Client([
            "base_uri" => self::$api_url,
        ]);
    }

    /**
     * [execute description]
     *
     * @param   string  $_method  [$_method description]
     * @param   array   $_param   [$_param description]
     *
     * @return  boolean|array            [return description]
     */
    protected static function execute(string $_method, array $_param)
    {
        self::init();
        $param = $_param;
        // if (!isset($param["headers"])) {
        //     $param["headers"] = ["Content-Type" => "application/json"];
        // }
        // $param["curl"] = [CURLOPT_PROXYTYPE => 7];
        // $param["proxy"] = "127.0.0.1:9050";
        try {
            $response = self::$guzzle_client->post($_method, $param);
            if (!$response || $response->getStatusCode() !== 200) {
                return false;
            }
            $updates = json_decode($response->getBody(), true);
            return $updates;
        } catch (\GuzzleHttp\Exception\GuzzleException $th) {
            var_dump($th);
            return false;
        }
    }

    /**
     * [getUpdates description]
     *
     * @param   int  $_offset  [$_offset description]
     *
     * @return  [type]         [return description]
     */
    public static function getUpdates(int $_offset)
    {
        return self::execute("getUpdates", ["query" => ["offset" => $_offset]]);
    }

    /**
     * [send_message description]
     *
     * @param   string  $_message      [$_message description]
     * @param   string  $_chat_id      [$_chat_id description]
     * @param   array   $reply_markup  [$reply_markup description]
     *
     * @return  []                     [return description]
     */
    public static function send_message(string $_message, $_chat_id, array $reply_markup = [])
    {
        $query = [
            "chat_id" => $_chat_id,
            "text" => $_message,
        ];
        if (!empty($reply_markup)) {
            $query["reply_markup"] = json_encode($reply_markup);
        }
        $execute = self::execute("sendMessage", [
            "query" => $query,
        ]);

        return $execute;
    }

    /**
     * [make_keyboard description]
     *
     * @param   array  $keyboards  [$keyboards description]
     * @param   bool   $_resize    [$_resize description]
     * @param   false              [ description]
     * @param   bool   $_once      [$_once description]
     * @param   false              [ description]
     *
     * @return  array             [return description]
     */
    public static function make_keyboard(array $keyboards, bool $_resize = false, bool $_once = false)
    {
        return [
            "keyboard" => $keyboards,
            "resize_keyboard" => $_resize,
            "one_time_keyboard" => $_once,
        ];
    }

    /**
     * [forward description]
     *
     * @param   string  $_chat_id       [$_chat_id description]
     * @param   string  $_from_chat_id  [$_from_chat_id description]
     * @param   string  $_message_id    [$_message_id description]
     *
     * @return  array
     */
    public static function forward($_chat_id, $_from_chat_id, $_message_id)
    {
        $query = [
            "chat_id" => $_chat_id,
            "from_chat_id" => $_from_chat_id,
            "message_id" => $_message_id,
        ];

        return self::execute("forwardMessage", [
            "query" => $query,
        ]);
    }

    public static function delete_message($_message_id, $_chat_id)
    {
        $query = [
            "chat_id" => $_chat_id,
            "message_id" => $_message_id,
        ];

        return self::execute("deleteMessage", [
            "query" => $query,
        ]);
    }

    /**
     * [send_file_request description]
     *
     * @param   int     $_chat_id  [$_chat_id description]
     * @param   string  $_path     [$_path description]
     * @param   string  $_caption  [$_caption description]
     *
     * @return  [type]             [return description]
     */
    public static function send_file($_chat_id, string $_path, string $_caption, string $_thumb = null)
    {
        $thumb = null;
        if (file_exists($_thumb)) {
            $thumb = [
                "Content-type" => "multipart/form-data",
                "name" => "thumb",
                "contents" => fopen($_thumb, "r"),
            ];
        }
        return self::execute("sendDocument", [
            "multipart" => [
                ["name" => "chat_id", "contents" => $_chat_id],
                [
                    "Content-type" => "multipart/form-data",
                    "name" => "document",
                    "contents" => fopen($_path, "r"),
                ],
                $thumb,
                [
                    "name" => "caption",
                    "contents" => $_caption,
                ],
            ],
        ]);
    }

    public static function send_photo($_chat_id, string $_path, string $_caption, string $_thumb = null)
    {
        return self::execute("sendPhoto", [
            "multipart" => [
                ["name" => "chat_id", "contents" => $_chat_id],
                [
                    "Content-type" => "multipart/form-data",
                    "name" => "photo",
                    "contents" => fopen($_path, "r"),
                ],
                // [
                //     "name" => "thumb",
                //     "contents" => fopen($_path, "r"),
                // ],
                [
                    "name" => "caption",
                    "contents" => $_caption,
                ],
            ],
        ]);
    }

    public static function send_audio($_chat_id, string $_path, string $_caption, string $_title, string $_thumb = null)
    {
        $thumb = null;
        if (file_exists($_thumb)) {
            $thumb = [
                "Content-type" => "multipart/form-data",
                "name" => "thumb",
                "contents" => fopen($_thumb, "r"),
            ];
        }
        return self::execute("sendAudio", [
            "multipart" => [
                ["name" => "chat_id", "contents" => $_chat_id],
                [
                    "Content-type" => "multipart/form-data",
                    "name" => "audio",
                    "contents" => fopen($_path, "r"),
                ],
                $thumb,
                [
                    "name" => "caption",
                    "contents" => $_caption,
                ],
                [
                    "name" => "title",
                    "contents" => $_title,
                ],
            ],
        ]);
    }
}
