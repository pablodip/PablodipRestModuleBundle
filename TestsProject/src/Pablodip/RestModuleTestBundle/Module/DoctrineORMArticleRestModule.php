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
            ->setOption('modelClass', 'Pablodip\RestModuleTestBundle\Entity\Article')
        ;
        $this->getOption('modelFields')->add(array(
            'title'   => array(),
            'content' => array(),
        ));
    }
}
