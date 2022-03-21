<?php

declare(strict_types=1);

namespace Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\Contact;

use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\ResourceResponseInterface;
use Webgriffe\SyliusActiveCampaignPlugin\ValueObject\Response\UpdateResourceResponseInterface;

final class UpdateContactResponse implements UpdateResourceResponseInterface
{
    /**
     * @param FieldValueResponse[] $fieldValues
     */
    public function __construct(
        private array $fieldValues,
        private UpdateContactContactResponse $contact
    ) {
    }

    /** @return FieldValueResponse[] */
    public function getFieldValues(): array
    {
        return $this->fieldValues;
    }

    public function getContact(): UpdateContactContactResponse
    {
        return $this->contact;
    }

    public function getResourceResponse(): ResourceResponseInterface
    {
        return $this->contact;
    }
}
