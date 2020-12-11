<?php

namespace App\Tests\Controller\Dashboard;

use App\Entity\ServiceGroup;
use App\Tests\TestUtilTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceGroupControllerTest extends WebTestCase
{
    use TestUtilTrait;

    public function testIndexIsSuccessful()
    {
        $client = self::createClient();
        $this->assertRouteResponseIsSuccessful($client, 'service-group_index');
    }

    public function testIndexWithNoGroup()
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('service-group_index'));
        dump($client->getResponse()->getContent());
        $this->assertSelectorTextContains('p', 'No service group is created yet.');
    }

    public function testIndexWithGroups()
    {
        $client = self::createClient();
        $groups = $this->loadFixture('service-group');
        $client->request('GET', $this->generateUrl('service-group_index'));

        foreach ($groups as $group) {
            $this->assertResponseContainsString($client, $group->getName());
        }
    }

    public function testNewIsSuccessful()
    {
        $client = self::createClient();
        $this->assertRouteResponseIsSuccessful($client, 'service-group_new');
    }

    public function testNewWithInvalidValues()
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('service-group_new'));
        $client->submitForm('Create', [
            'service_group[name]' => ''
        ]);
        $this->assertSelectorExists('.form-errors');
    }

    public function testNewWithValidValues()
    {
        $client = self::createClient();
        $client->request('GET', $this->generateUrl('service-group_new'));
        $client->submitForm('Create', [
            'service_group[name]' => 'service group name'
        ]);
        $this->assertResponseRedirectsRoute('service-group_index');
        $client->followRedirect();
        $this->assertResponseContainsString($client, 'service group name');
        $this->assertResponseContainsFlash('success', 'The service group "service group name" was created.');
    }

    public function testClickOnGroupInIndexRedirectsToEditPage()
    {
        $client = self::createClient();
        $group = $this->getFirstFixture();
        $client->request('GET', $this->generateUrl('service-group_index'));
        $client->clickLink($group->getName());
        $this->assertRouteSame('service-group_edit', ['id' => $group->getId()]);
    }

    private function getFirstFixture(): ServiceGroup
    {
        return $this->loadFixture('service-group')['service-group_1'];
    }

    public function testEditIsSuccessful()
    {
        $client = self::createClient();
        $group = $this->getFirstFixture();
        $this->assertRouteResponseIsSuccessful($client, 'service-group_edit', ['id' => $group->getId()]);
    }

    public function testEditWithInvalidValues()
    {
        $client = self::createClient();
        $group = $this->getFirstFixture();
        $client->request('GET', $this->generateUrl('service-group_edit', ['id' => $group->getId()]));
        $client->submitForm('Save', [
            'service_group[name]' => ''
        ]);
        $this->assertSelectorExists('.form-errors');
    }

    public function testEditWithValidValues()
    {
        $client = self::createClient();
        $group = $this->getFirstFixture();
        $client->request('GET', $this->generateUrl('service-group_edit', ['id' => $group->getId()]));
        $client->submitForm('Save', [
            'service_group[name]' => 'new name'
        ]);
        $this->assertResponseRedirectsRoute('service-group_index');
        $client->followRedirect();
        $this->assertResponseContainsFlash('success', 'The service group "new name" was edited.');
        $this->assertResponseContainsString($client, 'new name');
    }

    public function testDelete()
    {
        $client = self::createClient();
        $group = $this->getFirstFixture();
        $client->request('GET', $this->generateUrl('service-group_edit', ['id' => $group->getId()]));
        $client->submitForm('Delete');
        $this->assertResponseRedirectsRoute('service-group_index');
        $client->followRedirect();
        $this->assertResponseContainsFlash(
            'success',
            sprintf('The service group "%s" was deleted.', $group->getName())
        );
        $client->reload();
        $this->assertResponseNotContainsString($client, $group->getName());
    }
}
