<?php

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class Youtube
{
    public static function download($link)
    {
        $yt = new YoutubeDl();

        $collection = $yt->download(
            Options::create()
                ->downloadPath(DOWNLOAD_PATH)
                ->url($link)
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                echo $video->getTitle(); // Will return Phonebloks
                // $video->getFile(); // \SplFileInfo instance of downloaded file
            }
        }
    }
}
