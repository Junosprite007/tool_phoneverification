<?php

// phpcs:ignorefile

declare(strict_types=1);

/**
 * Infobip Client API Libraries OpenAPI Specification
 *
 * OpenAPI specification containing public endpoints supported in client API libraries.
 *
 * Contact: support@infobip.com
 *
 * This class is auto generated from the Infobip OpenAPI specification through the OpenAPI Specification Client API libraries (Re)Generator (OSCAR), powered by the OpenAPI Generator (https://openapi-generator.tech).
 *
 * Do not edit manually. To learn how to raise an issue, see the CONTRIBUTING guide or contact us @ support@infobip.com.
 */

namespace Infobip\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

class EmailReport implements ModelInterface
{
    public const DISCRIMINATOR = '';
    public const OPENAPI_MODEL_NAME = 'EmailReport';

    public const OPENAPI_FORMATS = [
        'applicationId' => null,
        'entityId' => null,
        'bulkId' => null,
        'messageId' => null,
        'to' => null,
        'sentAt' => 'date-time',
        'doneAt' => 'date-time',
        'messageCount' => 'int32',
        'price' => null,
        'status' => null,
        'error' => null
    ];

    /**
     */
    public function __construct(
        protected ?string $applicationId = null,
        protected ?string $entityId = null,
        protected ?string $bulkId = null,
        protected ?string $messageId = null,
        protected ?string $to = null,
        #[Serializer\Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.vP'])]

    protected ?\DateTime $sentAt = null,
        #[Serializer\Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.vP'])]

    protected ?\DateTime $doneAt = null,
        protected ?int $messageCount = null,
        #[Assert\Valid]

    protected ?\Infobip\Model\EmailPrice $price = null,
        #[Assert\Valid]

    protected ?\Infobip\Model\EmailStatus $status = null,
        #[Assert\Valid]

    protected ?\Infobip\Model\EmailReportError $error = null,
    ) {
    }

    #[Ignore]
    public function getModelName(): string
    {
        return self::OPENAPI_MODEL_NAME;
    }

    #[Ignore]
    public static function getDiscriminator(): ?string
    {
        return self::DISCRIMINATOR;
    }

    public function getApplicationId(): string|null
    {
        return $this->applicationId;
    }

    public function setApplicationId(?string $applicationId): self
    {
        $this->applicationId = $applicationId;
        return $this;
    }

    public function getEntityId(): string|null
    {
        return $this->entityId;
    }

    public function setEntityId(?string $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function getBulkId(): string|null
    {
        return $this->bulkId;
    }

    public function setBulkId(?string $bulkId): self
    {
        $this->bulkId = $bulkId;
        return $this;
    }

    public function getMessageId(): string|null
    {
        return $this->messageId;
    }

    public function setMessageId(?string $messageId): self
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getTo(): string|null
    {
        return $this->to;
    }

    public function setTo(?string $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getSentAt(): \DateTime|null
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function getDoneAt(): \DateTime|null
    {
        return $this->doneAt;
    }

    public function setDoneAt(?\DateTime $doneAt): self
    {
        $this->doneAt = $doneAt;
        return $this;
    }

    public function getMessageCount(): int|null
    {
        return $this->messageCount;
    }

    public function setMessageCount(?int $messageCount): self
    {
        $this->messageCount = $messageCount;
        return $this;
    }

    public function getPrice(): \Infobip\Model\EmailPrice|null
    {
        return $this->price;
    }

    public function setPrice(?\Infobip\Model\EmailPrice $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStatus(): \Infobip\Model\EmailStatus|null
    {
        return $this->status;
    }

    public function setStatus(?\Infobip\Model\EmailStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getError(): \Infobip\Model\EmailReportError|null
    {
        return $this->error;
    }

    public function setError(?\Infobip\Model\EmailReportError $error): self
    {
        $this->error = $error;
        return $this;
    }
}
