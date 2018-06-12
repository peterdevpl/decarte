<?php

declare(strict_types=1);

namespace Decarte\Shop\Service\View\Breadcrumb;

class Breadcrumb
{
    private $position;
    private $url;
    private $name;
    private $image;

    public function __construct(int $position, string $url, string $name, string $image = '')
    {
        $this->position = $position;
        $this->url = $url;
        $this->name = $name;
        $this->image = $image;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
