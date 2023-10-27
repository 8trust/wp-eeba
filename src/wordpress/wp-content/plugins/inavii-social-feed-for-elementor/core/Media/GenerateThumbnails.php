<?php

namespace Inavii\Instagram\Media;

class GenerateThumbnails extends Media
{

    public function generate(string $imageID, array $size = self::IMAGE_SIZE)
    {
        $imagePath = $this->getImageDir($imageID) . self::IMAGE_TYPE;

        if (file_exists($imagePath)) {
            $this->createThumbnails($imagePath, $imageID, $size);
        }

        return false;
    }

    private function createThumbnails(string $imagePath, string $imageID, array $size)
    {
        $editor = wp_get_image_editor($imagePath);

        if (!is_wp_error($editor)) {
            foreach ($size as $sizeName => $sizeNumber) {

                $fileNameDestination = $this->getImageDir($imageID) . '-' . $sizeName;

                if (file_exists($fileNameDestination)) {
                    return false;
                }

                @$editor->resize($sizeNumber, null, ['crop' => true]);

                @$editor->save($fileNameDestination, self::IMAGE_TYPE);
            }

            return false;
        }

        return false;
    }
}