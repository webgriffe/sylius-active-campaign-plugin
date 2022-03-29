<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusActiveCampaignPlugin\Integration\Client;

use Psr\Http\Message\RequestInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Webgriffe\SyliusActiveCampaignPlugin\Stub\HttpClientStub;
use Webgriffe\SyliusActiveCampaignPlugin\Client\ActiveCampaignResourceClient;
use Webgriffe\SyliusActiveCampaignPlugin\Model\ActiveCampaign\Contact;
use Webgriffe\SyliusActiveCampaignPlugin\Model\ActiveCampaign\FieldValue;
use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\Contact\ContactResponse;
use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\Contact\CreateContactResponse;
use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\Contact\ListContactsResponse;
use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\Contact\UpdateContactResponse;

final class ActiveCampaignContactClientTest extends KernelTestCase
{
    private ActiveCampaignResourceClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        HttpClientStub::setUp();

        $this->client = self::getContainer()->get('webgriffe.sylius_active_campaign_plugin.client.active_campaign.contact');
    }

    public function test_it_creates_contact_on_active_campaign(): void
    {
        HttpClientStub::$responseStatusCode = 201;
        HttpClientStub::$responseBodyContent = '{"fieldValues":[{"contact":"113","field":"1","value":"The Value for First Field","cdate":"2020-08-01T10:54:59-05:00","udate":"2020-08-01T14:13:34-05:00","links":{"owner":"https://:account.api-us1.com/api/3/fieldValues/11797/owner","field":"https://:account.api-us1.com/api/3/fieldValues/11797/field"},"id":"11797","owner":"113"},{"contact":"113","field":"6","value":"2008-01-20","cdate":"2020-08-01T10:54:59-05:00","udate":"2020-08-01T14:13:34-05:00","links":{"owner":"https://:account.api-us1.com/api/3/fieldValues/11798/owner","field":"https://:account.api-us1.com/api/3/fieldValues/11798/field"},"id":"11798","owner":"113"}],"contact":{"email":"johndoe@example.com","cdate":"2018-09-28T13:50:41-05:00","udate":"2018-09-28T13:50:41-05:00","orgid":"","links":{"bounceLogs":"https://:account.api-us1.com/api/:version/contacts/113/bounceLogs","contactAutomations":"https://:account.api-us1.com/api/:version/contacts/113/contactAutomations","contactData":"https://:account.api-us1.com/api/:version/contacts/113/contactData","contactGoals":"https://:account.api-us1.com/api/:version/contacts/113/contactGoals","contactLists":"https://:account.api-us1.com/api/:version/contacts/113/contactLists","contactLogs":"https://:account.api-us1.com/api/:version/contacts/113/contactLogs","contactTags":"https://:account.api-us1.com/api/:version/contacts/113/contactTags","contactDeals":"https://:account.api-us1.com/api/:version/contacts/113/contactDeals","deals":"https://:account.api-us1.com/api/:version/contacts/113/deals","fieldValues":"https://:account.api-us1.com/api/:version/contacts/113/fieldValues","geoIps":"https://:account.api-us1.com/api/:version/contacts/113/geoIps","notes":"https://:account.api-us1.com/api/:version/contacts/113/notes","organization":"https://:account.api-us1.com/api/:version/contacts/113/organization","plusAppend":"https://:account.api-us1.com/api/:version/contacts/113/plusAppend","trackingLogs":"https://:account.api-us1.com/api/:version/contacts/113/trackingLogs","scoreValues":"https://:account.api-us1.com/api/:version/contacts/113/scoreValues"},"id":"113","organization":""}}';
        $contact = new Contact('johndoe@example.com', 'John', 'Doe', '7223224241', [new FieldValue('1', 'The Value for First Field'), new FieldValue('6', '2008-01-20')]);

        $createdContact = $this->client->create($contact);

        self::assertCount(1, HttpClientStub::$sentRequests);
        $sentRequest = reset(HttpClientStub::$sentRequests);
        self::assertInstanceOf(RequestInterface::class, $sentRequest);
        self::assertEquals('/api/3/contacts', $sentRequest->getUri()->getPath());
        self::assertEquals('POST', $sentRequest->getMethod());
        self::assertEquals('{"contact":{"email":"johndoe@example.com","firstName":"John","lastName":"Doe","phone":"7223224241","fieldValues":[{"field":"1","value":"The Value for First Field"},{"field":"6","value":"2008-01-20"}]}}', $sentRequest->getBody()->getContents());

        self::assertNotNull($createdContact);
        self::assertInstanceOf(CreateContactResponse::class, $createdContact);
        self::assertEquals(113, $createdContact->getResourceResponse()->getId());
    }

    public function test_it_lists_contacts_on_active_campaign(): void
    {
        HttpClientStub::$responseStatusCode = 200;
        HttpClientStub::$responseBodyContent = '{"scoreValues":[],"contacts":[{"cdate":"2018-09-12T16:53:50-05:00","email":"adam@activecampaign.com","phone":"","firstName":"","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":"0000-00-00","ip":"0","ua":"","hash":"0d9c41ae7a4de516313673e2341f6003","socialdata_lastcheck":"0000-00-0000:00:00","email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":"0000-00-00","gravatar":"1","deleted":"0","anonymized":"0","udate":"2018-09-12T17:00:00-05:00","deleted_at":"0000-00-0000:00:00","scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/5/scoreValues"},"id":"5","organization":null},{"cdate":"2018-08-17T13:46:58-05:00","email":"kconnell2@gmailc.om","phone":"","firstName":"","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":"0000-00-00","ip":"2130706433","ua":"","hash":"4641d20634346d27408557fde5e3ad3b","socialdata_lastcheck":"0000-00-0000:00:00","email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":"0000-00-00","gravatar":"1","deleted":"0","anonymized":"0","adate":"2018-08-31T11:58:25-05:00","udate":"2018-08-17T13:46:58-05:00","deleted_at":"0000-00-0000:00:00","scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/4/scoreValues"},"id":"4","organization":null},{"cdate":"2018-09-18T11:02:57-05:00","email":"test@gmail.com","phone":"","firstName":"","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":null,"ip":"0","ua":null,"hash":"","socialdata_lastcheck":null,"email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":null,"gravatar":"1","deleted":"0","anonymized":"0","adate":null,"udate":"2018-09-18T11:02:57-05:00","edate":null,"deleted_at":null,"scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/6/scoreValues"},"id":"6","organization":null},{"cdate":"2018-08-17T09:56:33-05:00","email":"test@test.com","phone":"9813764","firstName":"Test","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":"0000-00-00","ip":"2130706433","ua":"","hash":"e4162c50b2edaf68b0d5012ef3cc82fd","socialdata_lastcheck":"0000-00-0000:00:00","email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":"0000-00-00","gravatar":"1","deleted":"0","anonymized":"0","adate":"2018-08-31T11:52:08-05:00","udate":"2018-08-17T09:56:33-05:00","edate":"2018-08-17T13:48:46-05:00","deleted_at":"0000-00-0000:00:00","scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/2/scoreValues"},"id":"2","organization":null},{"cdate":"2018-08-17T13:45:23-05:00","email":"test@testing.com","phone":"20405938","firstName":"testing","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":null,"ip":"2130706433","ua":null,"hash":"e3eba337bb1ede3bd073b1832e3f3def","socialdata_lastcheck":null,"email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":null,"gravatar":"1","deleted":"0","anonymized":"0","adate":null,"udate":"2018-08-17T13:45:23-05:00","edate":null,"deleted_at":null,"scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/3/scoreValues"},"id":"3","organization":null},{"cdate":"2018-09-19T23:11:11-05:00","email":"tjahn+test@activecampaign.com","phone":"","firstName":"","lastName":"","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":"0000-00-00","ip":"0","ua":"","hash":"853be08a2387ac13ca51dee72e586e9c","socialdata_lastcheck":"0000-00-0000:00:00","email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":"0000-00-00","gravatar":"0","deleted":"0","anonymized":"0","adate":"2018-09-19T23:24:43-05:00","udate":"2018-09-19T23:11:11-05:00","deleted_at":"0000-00-0000:00:00","scoreValues":[],"links":{"bounceLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/bounceLogs","contactAutomations":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactAutomations","contactData":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactData","contactGoals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactGoals","listContactsResponse":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/listContactsResponse","contactLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactLogs","contactTags":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactTags","contactDeals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/contactDeals","deals":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/deals","fieldValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/fieldValues","geoIps":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/geoIps","notes":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/notes","organization":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/organization","plusAppend":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/plusAppend","trackingLogs":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/trackingLogs","scoreValues":"https://test-enterprise-13.staging.listfly.com/api/3/contacts/7/scoreValues"},"id":"7","organization":null}],"meta":{"total":"6","page_input":{"segmentid":0,"formid":0,"listid":0,"tagid":0,"limit":20,"offset":0,"search":null,"sort":null,"seriesid":0,"waitid":0,"status":-1,"forceQuery":0,"cacheid":"522b5224f2007dca7483e08e7ebf5005"}}}';

        $listContactsResponse = $this->client->list(['email' => 'test@email.com']);

        self::assertCount(1, HttpClientStub::$sentRequests);
        $sentRequest = reset(HttpClientStub::$sentRequests);
        self::assertInstanceOf(RequestInterface::class, $sentRequest);
        self::assertEquals('/api/3/contacts', $sentRequest->getUri()->getPath());
        self::assertEquals('email=test%40email.com', $sentRequest->getUri()->getQuery());
        self::assertEquals('GET', $sentRequest->getMethod());

        self::assertNotNull($listContactsResponse);
        self::assertInstanceOf(ListContactsResponse::class, $listContactsResponse);
        self::assertCount(6, $listContactsResponse->getResourceResponseLists());
        self::assertInstanceOf(ContactResponse::class, $listContactsResponse->getResourceResponseLists()[0]);
        self::assertEquals('5', $listContactsResponse->getResourceResponseLists()[0]->getId());
    }

    public function test_it_updates_contact_on_active_campaign(): void
    {
        HttpClientStub::$responseStatusCode = 200;
        HttpClientStub::$responseBodyContent = '{"fieldValues":[{"contact":"113","field":"1","value":"The Value for First Field","cdate":"2020-08-01T10:54:59-05:00","udate":"2020-08-01T14:13:34-05:00","links":{"owner":"https://:account.api-us1.com/api/3/fieldValues/11797/owner","field":"https://:account.api-us1.com/api/3/fieldValues/11797/field"},"id":"11797","owner":"113"},{"contact":"113","field":"6","value":"2008-01-20","cdate":"2020-08-01T10:54:59-05:00","udate":"2020-08-01T14:13:34-05:00","links":{"owner":"https://:account.api-us1.com/api/3/fieldValues/11798/owner","field":"https://:account.api-us1.com/api/3/fieldValues/11798/field"},"id":"11798","owner":"113"}],"contact":{"cdate":"2018-09-28T13:50:41-05:00","email":"johndoe@example.com","phone":"","firstName":"John","lastName":"Doe","orgid":"0","segmentio_id":"","bounced_hard":"0","bounced_soft":"0","bounced_date":null,"ip":"0","ua":null,"hash":"8309146b50af1ed5f9cb40c7465a0315","socialdata_lastcheck":null,"email_local":"","email_domain":"","sentcnt":"0","rating_tstamp":null,"gravatar":"0","deleted":"0","anonymized":"0","adate":null,"udate":"2018-09-28T13:55:59-05:00","edate":null,"deleted_at":null,"created_utc_timestamp":"2018-09-28 13:50:41","updated_utc_timestamp":"2018-09-28 13:50:41","links":{"bounceLogs":"https://:account.api-us1.com/api/:version/contacts/113/bounceLogs","contactAutomations":"https://:account.api-us1.com/api/:version/contacts/113/contactAutomations","contactData":"https://:account.api-us1.com/api/:version/contacts/113/contactData","contactGoals":"https://:account.api-us1.com/api/:version/contacts/113/contactGoals","contactLists":"https://:account.api-us1.com/api/:version/contacts/113/contactLists","contactLogs":"https://:account.api-us1.com/api/:version/contacts/113/contactLogs","contactTags":"https://:account.api-us1.com/api/:version/contacts/113/contactTags","contactDeals":"https://:account.api-us1.com/api/:version/contacts/113/contactDeals","deals":"https://:account.api-us1.com/api/:version/contacts/113/deals","fieldValues":"https://:account.api-us1.com/api/:version/contacts/113/fieldValues","geoIps":"https://:account.api-us1.com/api/:version/contacts/113/geoIps","notes":"https://:account.api-us1.com/api/:version/contacts/113/notes","organization":"https://:account.api-us1.com/api/:version/contacts/113/organization","plusAppend":"https://:account.api-us1.com/api/:version/contacts/113/plusAppend","trackingLogs":"https://:account.api-us1.com/api/:version/contacts/113/trackingLogs","scoreValues":"https://:account.api-us1.com/api/:version/contacts/113/scoreValues"},"id":"113","organization":null}}';
        $contact = new Contact('johndoe@example.com', 'John', 'Doe', '7223224241', [new FieldValue('1', 'The Value for First Field'), new FieldValue('6', '2008-01-20')]);

        $updatedContact = $this->client->update(113, $contact);

        self::assertCount(1, HttpClientStub::$sentRequests);
        $sentRequest = reset(HttpClientStub::$sentRequests);
        self::assertInstanceOf(RequestInterface::class, $sentRequest);
        self::assertEquals('/api/3/contacts/113', $sentRequest->getUri()->getPath());
        self::assertEquals('PUT', $sentRequest->getMethod());
        self::assertEquals('{"contact":{"email":"johndoe@example.com","firstName":"John","lastName":"Doe","phone":"7223224241","fieldValues":[{"field":"1","value":"The Value for First Field"},{"field":"6","value":"2008-01-20"}]}}', $sentRequest->getBody()->getContents());

        self::assertNotNull($updatedContact);
        self::assertInstanceOf(UpdateContactResponse::class, $updatedContact);
        self::assertEquals(113, $updatedContact->getResourceResponse()->getId());
    }

    public function test_it_removes_contact_on_active_campaign(): void
    {
        HttpClientStub::$responseStatusCode = 200;
        HttpClientStub::$responseBodyContent = '{}';

        $this->client->remove(113);

        self::assertCount(1, HttpClientStub::$sentRequests);
        $sentRequest = reset(HttpClientStub::$sentRequests);
        self::assertInstanceOf(RequestInterface::class, $sentRequest);
        self::assertEquals('/api/3/contacts/113', $sentRequest->getUri()->getPath());
        self::assertEquals('DELETE', $sentRequest->getMethod());
        self::assertEmpty($sentRequest->getBody()->getContents());
    }
}
