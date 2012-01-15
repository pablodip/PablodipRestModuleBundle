<?php

namespace Pablodip\RestModuleTestBundle\Tests\Module;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    private $client;
    private $molino;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->molino = $this->registerMolino();
    }

    public function testListAction()
    {
        $this->loadFixtures();

        $this->client->request('GET', $this->getRoutePrefix());
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $nbArticles = $this->getNbArticles();

        $this->client->request('POST', $this->getRoutePrefix(), array(
            'title'   => 'foo',
            'content' => 'bar',
        ));
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame($nbArticles + 1, $this->getNbArticles());
        $article = $this->molino->createSelectQuery($this->getArticleClass())->sort('id', 'desc')->one();
        $this->assertSame('foo', $article->getTitle());
        $this->assertSame('bar', $article->getContent());
        $this->assertSame(array(
            'id'      => $article->getId(),
            'title'   => 'foo',
            'content' => 'bar',
        ), (array) json_decode($this->client->getResponse()->getContent()));
    }

    public function testCreateActionExtraFields()
    {
        $this->client = static::createClient();

        $this->client->request('POST', $this->getRoutePrefix(), array(
            'title'   => 'foo',
            'content' => 'bar',
            'ups'     => 'foobar',
        ));
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionValidationFails()
    {
        $this->client = static::createClient();

        $this->client->request('POST', $this->getRoutePrefix(), array(
            'title'   => '',
            'content' => 'bar',
        ));
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testShowAction()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $article = $this->getOneArticle();

        $this->client->request('GET', $this->getRoutePrefix().'/'.$article->getId());
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame($article->toArray(), (array) json_decode($this->client->getResponse()->getContent()));
    }

    public function testShowActionNotFound()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $this->client->request('GET', $this->getRoutePrefix().'/no');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateAction()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $article = $this->getOneArticle();
        $originalTitle = $article->getTitle();
        $nbArticles = $this->getNbArticles();

        $this->client->request('PUT', $this->getRoutePrefix().'/'.$article->getId(), array(
            'content' => 'bar',
        ));
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame($nbArticles, $this->getNbArticles());
        $this->molino->refreshModel($article);
        $this->assertSame($originalTitle, $article->getTitle());
        $this->assertSame('bar', $article->getContent());
        $this->assertSame($article->toArray(), (array) json_decode($this->client->getResponse()->getContent()));
    }

    public function testUpdateActionNotFound()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $this->client->request('PUT', $this->getRoutePrefix().'/no');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateActionExtraFields()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $article = $this->getOneArticle();

        $this->client->request('PUT', $this->getRoutePrefix().'/'.$article->getId(), array(
            'title'   => 'foo',
            'content' => 'bar',
            'ups'     => 'foobar',
        ));
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateActionValidationFails()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $article = $this->getOneArticle();

        $this->client->request('PUT', $this->getRoutePrefix().'/'.$article->getId(), array(
            'title'   => '',
            'content' => 'bar',
        ));
        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteAction()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $article = $this->getOneArticle();
        $id = $article->getId();
        $nbArticles = $this->getNbArticles();

        $this->client->request('DELETE', $this->getRoutePrefix().'/'.$id);
        $this->assertSame(204, $this->client->getResponse()->getStatusCode());

        $this->assertSame($nbArticles - 1, $this->getNbArticles());
        $this->assertNull($this->molino->createSelectQuery($this->getArticleClass())->filterEqual('id', $id)->one());
    }

    public function testDeleteActionNotFound()
    {
        $this->client = static::createClient();
        $this->loadFixtures();

        $this->client->request('DELETE', $this->getRoutePrefix().'/no');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    private function loadFixtures()
    {
        $this->cleanDatabase();

        for ($i = 0; $i < 10; $i++) {
            $article = $this->molino->createModel($this->getArticleClass());
            $article->setTitle('Article'.$i);
            $article->setContent('content');
            $this->molino->saveModel($article);
        }
    }

    private function getOneArticle()
    {
        return $this->molino->createSelectQuery($this->getArticleClass())->one();
    }

    private function getNbArticles()
    {
        return $this->molino->createSelectQuery($this->getArticleClass())->count();
    }

    abstract protected function getRoutePrefix();

    abstract protected function registerMolino();

    abstract protected function getArticleClass();

    abstract protected function cleanDatabase();
}
