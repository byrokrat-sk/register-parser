<?php

declare(strict_types=1);

namespace ByrokratSk\BusinessRegister\Model;

use ByrokratSk\Helper\Arrayable;
use DateTime;
use JsonSerializable;

use function array_filter;
use function array_map;

class VersionableGroup implements JsonSerializable, Arrayable
{
    /**
     * @param Versionable[] $Items
     */
    public function __construct(/** @var Versionable[] */
        private array $Items,
    ) {}

    public function isEmpty(): bool
    {
        return [] === $this->Items;
    }

    public function getLatest(): ?Versionable
    {
        return $this->Items[0] ?? null;
    }

    /** @returns Versionable[] */
    public function getValid(?DateTime $now = null): array
    {
        $now ??= new DateTime();

        return array_filter(
            $this->Items,
            static fn(Versionable $versionable): bool => (
                !$versionable->ValidTo instanceof DateTime
                || $now > $versionable->ValidTo
            ),
        );
    }

    /** @returns Versionable[] */
    public function getExpired(?DateTime $now = null): array
    {
        $now ??= new DateTime();

        return array_filter(
            $this->Items,
            static fn(Versionable $versionable): bool => (
                !$versionable->ValidTo instanceof DateTime
                || $now <= $versionable->ValidTo
            ),
        );
    }

    /** @returns Versionable[] */
    public function getAll(): array
    {
        return $this->Items;
    }

    public function toArray(): array
    {
        return array_map(static fn(Arrayable $arrayable): array => $arrayable->toArray(), $this->getAll());
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
