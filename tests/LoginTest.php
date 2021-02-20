<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class LoginTest extends WebTestCase
{
    public function testLoginSuccessful(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
    
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        $form = $crawler->filter("form[name=login]")->form([
           "email" => 'user@gmail.com',
           "password" => 'dev',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame('index');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testLoginFailBadEmail(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        $form = $crawler->filter("form[name=login]")->form([
               "email" => 'fail@gmail.com',
               "password" => 'dev',
           ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertRouteSame('security_login');
    }
    
    public function testLoginFailWrongPassword(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        $form = $crawler->filter("form[name=login]")->form([
                   "email" => 'user@gmail.com',
                   "password" => 'fail',
               ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertRouteSame('security_login');
    }
    
    public function testLoginFailWrongCsrf(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        $form = $crawler->filter("form[name=login]")->form([
               "email" => 'user@gmail.com',
               "password" => 'dev',
               "_csrf_token" => 'fail',
           ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertRouteSame('security_login');
    }
}