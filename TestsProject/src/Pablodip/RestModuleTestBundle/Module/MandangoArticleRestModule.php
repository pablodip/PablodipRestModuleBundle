<?php

namespace Pablodip\RestModuleTestBundle\Module;

use Pablodip\RestModuleBundle\Module\MandangoRestModule;

class MandangoArticleRestModule extends MandangoRestModule
{
    protected function configure()
    {
        $this
            ->setRouteNamePrefix('mandango_articles_')
            ->setRoutePatternPrefix('/mandango/articles')
            ->setOption('model_class', 'Model\PablodipRestModuleTestBundle\Article')
        ;
        $this->getOption('model_fields')->add(array(
            'title'   => array(),
            'content' => array(),
        ));
    }
}
