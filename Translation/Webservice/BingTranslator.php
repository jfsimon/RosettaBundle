<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Bing translation webservice implementation (http://www.microsofttranslator.com/).
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BingTranslator extends AbstractTranslator implements TranslatorInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
