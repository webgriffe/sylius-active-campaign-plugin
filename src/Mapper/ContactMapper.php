<?php

declare(strict_types=1);

namespace Webgriffe\SyliusActiveCampaignPlugin\Mapper;

use Sylius\Component\Core\Model\CustomerInterface;
use Webgriffe\SyliusActiveCampaignPlugin\Exception\CustomerDoesNotHaveEmailException;
use Webgriffe\SyliusActiveCampaignPlugin\Factory\ActiveCampaignContactFactoryInterface;
use Webgriffe\SyliusActiveCampaignPlugin\Model\ActiveCampaignContactInterface;

final class ContactMapper implements ContactMapperInterface
{
    public function __construct(
        private ActiveCampaignContactFactoryInterface $contactFactory
    ) {
    }

    public function mapFromCustomer(CustomerInterface $customer): ActiveCampaignContactInterface
    {
        $customerEmail = $customer->getEmail();
        if ($customerEmail === null) {
            throw new CustomerDoesNotHaveEmailException(sprintf(
                'Unable to create a new ActiveCampaign Contact, the customer "%s" does not have a valid email.',
                (string) $customer->getId()
            ));
        }

        return $this->contactFactory->createNewFromEmail($customerEmail);
    }
}
