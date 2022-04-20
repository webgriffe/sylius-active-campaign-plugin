<?php

declare(strict_types=1);

namespace Webgriffe\SyliusActiveCampaignPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Webgriffe\SyliusActiveCampaignPlugin\Message\Contact\ContactListsUpdater;
use Webgriffe\SyliusActiveCampaignPlugin\Repository\ActiveCampaignCustomerRepositoryInterface;
use Webmozart\Assert\Assert;

/** @psalm-suppress PropertyNotSetInConstructor */
final class WebhookController extends AbstractController
{
    public function __construct(
        private ActiveCampaignCustomerRepositoryInterface $customerRepository,
        private MessageBusInterface $messageBus
    ) {
    }

    public function updateListStatusAction(Request $request): Response
    {
        $type = $request->request->getAlpha('type');
        if ($type !== 'subscribe' && $type !== 'unsubscribe') {
            throw new BadRequestException(sprintf('Only "%s" types are allowed for this webhook', implode(', ', ['subscribe', 'unsubscribe'])));
        }
        /** @var array{"id":string, "email":string,"first_name":string,"last_name":string,"phone":string,"ip":string,"tags":string,"customer_acct_name":string,"orgname":string} $contact */
        $contact = (array) $request->request->get('contact');

        $customer = $this->customerRepository->findByContactId((int) $contact['id']);
        if ($customer === null) {
            return new Response('Customer for contact not found.');
        }
        /** @var string|int|null $customerId */
        $customerId = $customer->getId();
        Assert::notNull($customerId);

        $this->messageBus->dispatch(new ContactListsUpdater($customerId));

        return new Response('OK');
    }
}