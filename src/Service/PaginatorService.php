<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;

class PaginatorService
{
    protected $perPage;

    protected $result;

    protected $currentPage;

    protected $totalItems;

    public function __construct()
    {
        $this->perPage = 25;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage($perPage): self
    {
        if (is_numeric($perPage)) {
            $this->perPage = $perPage;
        } else {
            $this->perPage = 25;
        }

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getFirstResult(): int
    {
        return intval($this->getPerPage() * ($this->getCurrentPage() - 1));
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage): self
    {
        if ($currentPage == 0 || $currentPage == '') {
            $currentPage = 1;
        }
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getLastPage(): int
    {
        if ($this->totalItems <= 0) {
            return 1;
        }

        if ($this->perPage == 0) {
            return $this->getTotalItems();
        }

        return intval(ceil($this->totalItems / $this->perPage));
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder, ?string $distinct = null): self
    {
        $query = clone $queryBuilder;

        if (!$distinct) {
            $aliases = $queryBuilder->getRootAliases();
            $count = $query->select('COUNT(DISTINCT('.$aliases[0].')) as count')
                ->getQuery()
                ->getResult();
        } else {
            $count = $query->select('COUNT(DISTINCT('.$distinct.')) as count')
                ->getQuery()
                ->getResult();
        }

        $count = isset($count[0]['count']) ? $count[0]['count'] : 1;

        $this->setTotalItems($count);

        return $this;
    }

    public function setCountQueryBuilder(QueryBuilder $queryBuilder) : self
    {
        $query = clone $queryBuilder;

        $data = $query->getQuery()
            ->getResult();

        $count = count($data);

        $this->setTotalItems($count);

        return $this;
    }
}