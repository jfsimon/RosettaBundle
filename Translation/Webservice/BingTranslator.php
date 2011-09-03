<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Google translate webservice implementation.
 * Will sadly be unavailable from december 2011.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BingTranslator extends AbstractTranslator implements TranslatorInterface
{
    /**
     * @throws \InvalidArgumentException
     *
     * @param string $text
     * @param string $fromLocale
     * @param string $toLocale
     * @return Request
     */
    protected function buildRequest($text, $fromLocale, $toLocale)
    {
        $request = Request::get('http://api.bing.net/json.aspx')
            ->addParameter('Sources', 'Translation')
            ->addParameter('Query', $text)
            ->addParameter('Translation.SourceLanguage', $fromLocale)
            ->addParameter('Translation.TargetLanguage', $toLocale)
        ;

        if ($appId = $this->getOption('api_key')) {
            $request->addParameter('AppId', $appId);
        } else {
            throw new \InvalidArgumentException('Bing translator requires "api_key" option.');
        }

        if ($version = $this->getOption('version')) {
            $request->addParameter('Version', $version);
        }

        return $request;
    }

    /**
     * @param array $response
     *
     * @return string|null
     */
    protected function parseResponse(array $response)
    {
        $response = $response['SearchResponse'];

        if (isset($response['Errors'])) {
            foreach ($response['Errors'] as $error) {
                $this->error = $error['Message'];
            }

            return null;
        }

        return $response['Translation']['Results'][0]['TranslatedTerm'];
    }
}
