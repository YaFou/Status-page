<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait TestUtilTrait
{
    use FixturesTrait;

    private static function assertResponseContainsString(KernelBrowser $client, string $needle): void
    {
        self::assertStringContainsString($needle, $client->getResponse()->getContent());
    }

    private static function assertResponseNotContainsString(KernelBrowser $client, string $needle): void
    {
        self::assertStringNotContainsString($needle, $client->getResponse()->getContent());
    }

    private static function assertResponseContainsFlash(string $type, string $message): void
    {
        self::assertSelectorTextSame('div.flash', $message);
    }

    private static function assertResponseRedirectsRoute(string $name, array $parameters = []): void
    {
        self::assertResponseRedirects(self::generateUrl($name, $parameters));
    }

    private static function generateUrl(string $name, array $parameters = []): string
    {
        return self::get('router')->generate($name, $parameters);
    }

    private static function get(string $id)
    {
        return self::$container->get($id);
    }

    private static function assertRouteResponseIsSuccessful(
        KernelBrowser $client,
        string $name,
        array $parameters = []
    ): void
    {
        $client->request('GET', self::generateUrl($name, $parameters));
        self::assertResponseIsSuccessful();
    }

    private function loadFixture(string $name): array
    {
        return $this->loadFixtureFiles([
            __DIR__ .
            DIRECTORY_SEPARATOR .
            'fixtures' .
            DIRECTORY_SEPARATOR .
            str_replace('/', DIRECTORY_SEPARATOR, $name) .
            '.yaml'
        ]);
    }
}
