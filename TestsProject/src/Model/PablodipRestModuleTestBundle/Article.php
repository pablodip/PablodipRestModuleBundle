<?php

namespace Model\PablodipRestModuleTestBundle;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Model\PablodipRestModuleTestBundle\Article document.
 */
class Article extends \Pablodip\RestModuleTestBundle\Model\Article implements NormalizableInterface
{
    public function normalize(NormalizerInterface $normalizer, $format = null)
    {
        return $this->toArray();
    }
}
