<?php

namespace Decarte\Shop\Service\Schema;

use Decarte\Shop\Service\View\Breadcrumb\BreadcrumbList;
use Spatie\SchemaOrg\Schema;

class BreadcrumbListSchema
{
    public function generateData(BreadcrumbList $breadcrumbs): string
    {
        $items = [];
        foreach ($breadcrumbs->getList() as $breadcrumb) {
            $item = Schema::thing()->setProperty('@id', $breadcrumb->getUrl())->name($breadcrumb->getName());
            if ($breadcrumb->getImage()) {
                $item->image($breadcrumb->getImage());
            }

            $items[] = Schema::listItem()->position($breadcrumb->getPosition())->item($item);
        }

        $schema = Schema::breadcrumbList()->itemListElement($items);

        return $schema->toScript();
    }
}
