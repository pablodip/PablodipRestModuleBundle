<?php

namespace Pablodip\RestModuleTestBundle\Module;

use Pablodip\RestModuleBundle\Module\DoctrineORMRestModule;

class DoctrineORMArticleRestModule extends DoctrineORMRestModule
{
    protected function configure()
    {
        $this
            ->setRouteNamePrefix('doctrine_orm_articles_')
            ->setRoutePatternPrefix('/doctrine/orm/articles')
            ->setOption('model_class', 'Pablodip\RestModuleTestBundle\Entity\Article')
        ;
        $this->getOption('model_fields')->add(array(
            'title'   => array(),
            'content' => array(),
        ));
    }
}
