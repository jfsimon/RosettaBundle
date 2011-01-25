<?php

namespace Bundle\RosettaBundle\Service\Translator;

class GoogleAdapter extends Adapter implements AdapterInterface
{
    public function translate($text, $from, $to)
    {
        switch($this->config['version'] ?: 1)
        {
            case 1:
                $uri = 'https://ajax.googleapis.com/ajax/services/language/translate?'
                    .'v=1.0&q='.urlencode($text)
                    .(isset($_SERVER['REMOTE_ADDR'])?'&userip='.$_SERVER['REMOTE_ADDR']:'')
                    .($this->config['key']?'&key='.$this->config['key']:'')
                    .'&langpair='.$from.'%7C'.$to;
                $parse = 'parseV1Response';
                break;

            case 2:
                $uri = 'https://www.googleapis.com/language/translate/v2?'
                    .'&q='.urlencode($text)
                    .($this->config['key']?'&key='.$this->config['key']:'')
                    .'&target='.$to.'&source='.$from;

                die($uri);
                $parse = 'parseV2Response';
                break;

            default:
                throw new \InvalidArgumentException('Unknown GoogleAdapter version : '.$this->config['version']);
        }

        return $this->$parse($this->sendRequest($uri));
    }

    protected function sendRequest($uri)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_REFERER, '');
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    protected function parseV1Response($response)
    {
        $json = json_decode($response);

        if((int)$json->responseStatus != 200) {
            throw new \RuntimeException($json['responseDetails']);
        }

        return $json->responseData->translatedText;
    }

    protected function parseV2Response($response)
    {
        $json = json_decode($response);

        try {
            return $json->data->translations[0]->trasnlatedText;
        } catch(\Exception $e) {
            throw new \RuntimeException('Google translation v2 failed');
        }
    }
}