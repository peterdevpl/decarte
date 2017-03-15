<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;

trait SortableRepositoryTrait
{
    /**
     * Returns name of the field which is used to group entities.
     * Entities will be sorted within this group.
     * For example, products are grouped into series, series are grouped into collections etc.
     *
     * If you specify a name "fooType", you'll need a getFooType() method in your entity class.
     *
     * If your entity does not belong to any group, return null.
     *
     * @return string|null
     */
    abstract protected function getSortGroupField();

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
        $swapObject = $this->findSwapObject($currentObject, $operator, $sortOrder);

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

    protected function findSwapObject($currentObject, string $operator, string $sortOrder)
    {
        $swapCriteria = Criteria::create()
            ->where(Criteria::expr()->$operator('sort', $currentObject->getSort()));

        $groupField = $this->getSortGroupField();
        if ($groupField) {
            $method = 'get' . ucfirst($groupField);
            $group = $currentObject->$method();
            $swapCriteria->andWhere(Criteria::expr()->eq($groupField, $group));
        }

        $swapCriteria
            ->orderBy(['sort' => $sortOrder])
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $this->matching($swapCriteria)->first();
    }

    /**
     * Fetches next sorting number for an entity.
     * @param $object
     * @return int
     */
    public function fetchSortNumber($object)
    {
        $group = null;
        $groupField = $this->getSortGroupField();
        if ($groupField) {
            $method = 'get' . ucfirst($groupField);
            $group = $object->$method();
        }

        /** @var \Doctrine\ORM\QueryBuilder */
        $queryBuilder = $this->createQueryBuilder('obj');
        $queryBuilder
            ->add('select', $queryBuilder->expr()->sum($queryBuilder->expr()->max('obj.sort'), '1'));

        if ($group) {
            $queryBuilder->where("obj.{$groupField} = :group")->setParameter(':group', $group);
        }

        $query = $queryBuilder->getQuery();
        return (int) $query->getResult()[0][1];
    }
}
