<?php

namespace Decarte\Shop\Service\View\Breadcrumb;

class BreadcrumbList
{
    private $list = [];

    public function add(Breadcrumb $breadcrumb): self
    {
        $this->list[] = $breadcrumb;
        return $this;
    }

    public function build(string $url, string $name, string $image = ''): self
    {
        $this->list[] = new Breadcrumb(count($this->list) + 1, $url, $name, $image);
        return $this;
    }

    public function getList(): array
    {
        return $this->list;
    }
}
