<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Google translate v2 webservice implementation (http://code.google.com/apis/language/translate/overview.html).
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GoogleTranslator extends AbstractTranslator implements TranslatorInterface
{
    /**
     * {@inheritdoc}
     */
    protected function buildRequest($text, $fromLocale, $toLocale)
    {
        $request = Request::get('https://www.googleapis.com/language/translate/v2')
            ->addParameter('q', $text)
            ->addParameter('source', $fromLocale)
            ->addParameter('target', $toLocale)
        ;

        if ($apiKey = $this->getOption('api_key')) {
            $request->addParameter('key', $apiKey);
        } else {
            throw new \InvalidArgumentException('Google translator requires "api_key" option.');
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseResponse(array $response)
    {
        if (isset($response['error'])) {
            $this->error = $response['error']['errors'][0]['message'];

            return null;
        }

        return $response['data']['translations'][0]['translatedText'];
    }
}
