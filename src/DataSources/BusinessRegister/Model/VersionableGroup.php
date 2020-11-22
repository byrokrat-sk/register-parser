<?php


namespace SkGovernmentParser\DataSources\BusinessRegister\Model;


use SkGovernmentParser\Helper\Arrayable;

class VersionableGroup implements \JsonSerializable, Arrayable
{
    /** @var Versionable[] */
    private array $Items;

    public function __construct($Items)
    {
        $this->Items = $Items;
    }

    public function isEmpty(): bool
    {
        return empty($this->Items);
    }

    public function getLatest(): ?Versionable
    {
        return $this->Items[0] ?? null;
    }

    /** @returns Versionable[] */
    public function getValid(\DateTime $now = null): array
    {
        $now = $now ?? new \DateTime();

        return array_filter($this->Items, function (Versionable $versionable) use ($now) {
            return is_null($versionable->ValidTo) || $now > $versionable->ValidTo;
        });
    }

    /** @returns Versionable[] */
    public function getExpired(\DateTime $now = null): array
    {
        $now = $now ?? new \DateTime();

        return array_filter($this->Items, function (Versionable $versionable) use ($now) {
            return is_null($versionable->ValidTo) || $now <= $versionable->ValidTo;
        });
    }

    /** @returns Versionable[] */
    public function getAll(): array
    {
        return $this->Items;
    }

    public function toArray(): array
    {
        return array_map(function (Arrayable $arrayable) {
            return $arrayable->toArray();
        }, $this->getAll());
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
