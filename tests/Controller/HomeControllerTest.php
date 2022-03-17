<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1HomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Zero Blog');
    }

    public function testLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testAdminPage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->catchExceptions(false);
        //dd($this->client->getResponse());
    }

    /*public function testRedirectPage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
        $client->catchExceptions(false);
    }*/
}
