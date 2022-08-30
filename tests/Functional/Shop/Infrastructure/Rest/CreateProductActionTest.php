<?php
declare(strict_types=1);

namespace App\Tests\Functional\Shop\Infrastructure\Rest;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CreateProductActionTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient([],['HTTP_X-AUTH-TOKEN' => '$2y$13$jxGxcIuqDju']);
    }
    /**
     * @expectsException Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     **/
    public function testProductSuccessfullyCreated(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name'=>'test product', 'description'=>'test description', 'price' => ['amount' => 2, 'currency' => 'PLN']]),
            );


        self::assertResponseIsSuccessful();
    }

}
