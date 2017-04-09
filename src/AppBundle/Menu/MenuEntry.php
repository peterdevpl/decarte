<?php

namespace AppBundle\Menu;

class MenuEntry
{
    protected $routeName = '';

    protected $title = '';

    protected $routeParams = [];

    protected $cssClass = '';

    public function __construct(string $routeName, string $title, array $routeParams = [], string $cssClass = '')
    {
        $this->routeName = $routeName;
        $this->title = $title;
        $this->routeParams = $routeParams;
        $this->cssClass = $cssClass;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }
}
