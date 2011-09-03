<?php

namespace BeSimple\RosettaBundle\Translation\Webservice;

/**
 * Gengo webservice implementation (http://www.mygengo.com).
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class GengoTranslator extends AbstractTranslator implements TranslatorInterface
{
    /**
     * {@inheritdoc}
     */
    protected function buildRequest($text, $fromLocale, $toLocale)
    {
        $data = array(
            'job' => array(
                'type'     => 'text',
                'slug'     => 'Translating '.$fromLocale.' to '.$toLocale.' with the myGengo API',
                'body_src' => utf8_encode($text),
                'lc_src'   => $fromLocale,
                'lc_tgt'   => $toLocale,
                'tier'     => 'machine',
            )
        );

        $request = Request::post('http://api.mygengo.com/v1/translate/job')
            ->addHeader('Accept: application/json')
        ;

        if ($apiKey = $this->getOption('api_key')) {
            $request->addParameter('api_key', $apiKey);
        } else {
            throw new \InvalidArgumentException('MyGengo translator requires "api_key" option.');
        }

        // parameters must be set in alphabetical order
        $request
            ->addParameter('data', json_encode($data))
            ->addParameter('ts', (string) time())
        ;

        if ($privateKey = $this->getOption('private_key')) {
            $request->addParameter('api_sig', hash_hmac('sha1', json_encode($request->getParameters()), $privateKey));
        } else {
            throw new \InvalidArgumentException('MyGengo translator requires "private_key" option.');
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseResponse(array $response)
    {
        if ($response['opstat'] === 'error') {
            $this->error = $response['err']['msg'];

            return null;
        }

        return $response['response']['job']['body_tgt'];
    }
}
