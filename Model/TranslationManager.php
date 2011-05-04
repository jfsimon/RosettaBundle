<?php

namespace BeSimple\RosettaBundle\Model;

class MessageManager extends Manager
{
    public function get(Message $domain, $locale, $text)
    {
        return $this->repository->findOneBy(array('message' => $message->getId(), 'locale' => $locale, 'hash' => Translation::hash($text)))
                ? : $this->factory->translation($message, $locale, $text);
    }

    public function save(Translation $translation)
    {
        $this->repository->persist($translation);
        $this->repository->flush();
    }

    public function saveCollection(TranslationCollection $translations)
    {
        foreach ($translations as $translation) {
            $this->repository->persist($translation);
        }

        $this->repository->flush();
    }

    public function findByMessage(Message $message)
    {
        return $this->repository->findBy(array('message' => $message->getId()));
    }

    public function findByLocale($locale)
    {
        return $this->repository->findBy(array('locale' => $locale));
    }

    public function findByMessageAndLocale(Message $message, $locale)
    {
        return $this->repository->findBy(array('message' => $message->getId(), 'locale' => $locale));
    }
}
