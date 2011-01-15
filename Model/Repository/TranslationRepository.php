<?php

namespace Bundle\RosettaBundle\Model\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Bundle\RosettaBundle\Model\Entity\Message;
use Bundle\RosettaBundle\Model\Entity\Domain;

class MessageRepository extends Repository
{
    public function getAll($message=null, $language=null)
    {
        return $this
            ->getQueryBuilder($message, $language)
            ->orderBy('t.rating', 'DESC')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getChoosen($message, $language)
    {
        return $this
            ->getQueryBuilder($message, $language, false)
            ->where('t.isChoosen = :choosen')
            ->setParameter('choosen', true)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    protected function getQueryBuilder($message=null, $language=null, $acceptNull=true) {
        $qb = $this->createQueryBuilder('t');
        $qb = $this->filterMessage($qb, $message, $acceptNull);
        $qb = $this->filterLanguage($qb, $language, $acceptNull);

        return $qb;
    }

    protected function filterMessage(QueryBuilder $qb, $message=null, $acceptNull=true)
    {
        if(is_null($message) && $acceptNull) {
            return $qb;
        }

        if(is_string($message)) {
            return $qb
                ->innerJoin('messages', 'm', Join::WITH, 'm.hash = :message')
                ->setParameter('message', Message::hash($message))
            ;
        }

        if(is_object($message) && $message instanceof Message) {
            return $qb
                ->innerJoin('messages', 'm', Join::WITH, 'm.id = :message')
                ->setParameter('message', $message->getId())
            ;
        }

        throw new \InvalidArgumentException('Message must be '.($acceptNull ? 'null or ':'')
            .'string or instance of Bundle\\RosettaBundle\\Model\\Repository\\Message');
    }

    protected function filterLanguage(QueryBuilder $qb, $language=null, $acceptNull=true)
    {
        if(is_null($language) && $acceptNull) {
            return $qb;
        }

        if(is_string($language)) {
            return $qb
                ->innerJoin('language', 'l', Join::WITH, 'l.code = :language')
                ->setParameter('language', $language)
            ;
        }

        if(is_object($language) && $language instanceof Language) {
            return $qb
                ->innerJoin('language', 'l', Join::WITH, 'l.id = :language')
                ->setParameter('language', $language->getId())
            ;
        }

        throw new \InvalidArgumentException('Language must be '.($acceptNull ? 'null or ':'')
            .'string or instance of Bundle\\RosettaBundle\\Model\\Repository\\Language');
    }
}