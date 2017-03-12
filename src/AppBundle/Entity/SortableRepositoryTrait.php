<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;

trait SortableRepositoryTrait
{
    public function moveUp(int $id)
    {
        $this->move($id, 'lt', Criteria::DESC);
    }

    public function moveDown(int $id)
    {
        $this->move($id, 'gt', Criteria::ASC);
    }

    protected function move(int $id, string $operator, string $sortOrder)
    {
        $currentObject = $this->find($id);

        $swapCriteria = Criteria::create()
            ->where(Criteria::expr()->$operator('sort', $currentObject->getSort()))
            ->orderBy(['sort' => $sortOrder])
            ->setFirstResult(0)
            ->setMaxResults(1);
        $swapObject = $this->matching($swapCriteria)->first();

        if ($swapObject) {
            $swapSort = $swapObject->getSort();
            $swapObject->setSort($currentObject->getSort());
            $currentObject->setSort($swapSort);

            $em = $this->getEntityManager();
            $em->beginTransaction();
            $em->persist($swapObject);
            $em->persist($currentObject);
            $em->flush();
            $em->commit();
        }
    }
}