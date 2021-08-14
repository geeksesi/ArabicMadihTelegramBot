<?php
namespace Lib;
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
                ->extractAudio(true)
                ->audioFormat("mp3")
                ->audioQuality(0) // best
                ->output("1443-%(title)s.%(ext)s")
                ->embedThumbnail(true)
                ->writeThumbnail(true)
                ->addMetadata(true)
                ->url($link)
        );
        $files = [];
        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
                continue;
            } else {
                echo $video->getTitle(); // Will return Phonebloks
                $file = $video->getFile(); // \SplFileInfo instance of downloaded file
                $files[] = ["mp3" => $file->getPathname(), "title" => $video->getTitle()];
            }
        }
        return $files;
    }
}
