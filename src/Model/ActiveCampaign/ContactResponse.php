<?php

declare(strict_types=1);

namespace Webgriffe\SyliusActiveCampaignPlugin\Model\ActiveCampaign;

final class ContactResponse implements ContactResponseInterface
{
    /**
     * @param FieldValueInterface[] $fieldValues
     * @param array<string, string> $links
     */
    public function __construct(
        private array $fieldValues,
        private string $email,
        private string $createdAt,
        private string $updatedAt,
        private string $originalId,
        private array $links,
        private string $id,
        private string $organization
    ) {
    }

    public function getFieldValues(): array
    {
        return $this->fieldValues;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getOriginalId(): string
    {
        return $this->originalId;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrganization(): string
    {
        return $this->organization;
    }
}
