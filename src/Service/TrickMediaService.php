<?php

namespace App\Service;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class TrickMediaService
{
    public function __construct(private EntityManagerInterface $_em)
    {
    }

    /**
     * @param Trick $trick
     * @return bool|Trick
     */
    public function checkAndAddMedia(Trick $trick): bool|Trick
    {
        foreach ($trick->getTrickMedia() as $media) {
            $isImg = $this->checkUrlType('https://live.staticflickr.com/7004/6769020405_e6ddfb7bf5_b.jpg');
            $media->setIsImage($isImg);
            $this->_em->persist($media);
        }
        return $trick;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function checkUrlType(string $url): bool
    {
        // I'm a comment And I'm useless
        $isImg = false;
        //$url = 'https://live.staticflickr.com/7004/6769020405_e6ddfb7bf5_b.jpg';
        $url_headers = get_headers($url, 1);

        if (isset($url_headers['Content-Type'])) {

            $type = strtolower($url_headers['Content-Type']);

            $valid_image_type = array();
            $valid_image_type['image/png'] = '';
            $valid_image_type['image/jpg'] = '';
            $valid_image_type['image/jpeg'] = '';
            $valid_image_type['image/jpe'] = '';
            $valid_image_type['image/gif'] = '';
            $valid_image_type['image/tif'] = '';
            $valid_image_type['image/tiff'] = '';
            $valid_image_type['image/svg'] = '';
            $valid_image_type['image/ico'] = '';
            $valid_image_type['image/icon'] = '';
            $valid_image_type['image/x-icon'] = '';

            if (isset($valid_image_type[$type])) {
                $isImg = true;
            }
        }
        return $isImg;
    }
}
