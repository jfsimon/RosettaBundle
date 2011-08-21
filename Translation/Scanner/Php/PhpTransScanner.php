<?php

namespace BeSimple\RosettaBundle\Translation\Scanner\Php;

use BeSimple\RosettaBundle\Translation\Scanner\ScannerInterface;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Parser\Parser;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Statement\FunctionCallStatement;
use BeSimple\RosettaBundle\Translation\Scanner\Exception\ScannerException;
use BeSimple\RosettaBundle\Translation\Scanner\Php\Resolver\ContextResolver;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class PhpTransScanner extends AbstractPhpScanner implements ScannerInterface
{
    protected $methodName      = 'trans';
    protected $parametersIndex = 1;
    protected $domainIndex     = 2;
    protected $isChoice        = false;

    /**
     * {@inheritdoc}
     */
    public function scan($source, array $context = array())
    {
        $messages = array();
        $parser   = new Parser();

        $parser->parse($source, array(new ContextResolver($context)));

        foreach ($parser->extractMethodCalls($this->methodName) as $functionCall) {
            $messages[] = $this->scanFunctionCall($functionCall, array('text' => null, 'domain' => 'messages', 'parameters' => null, 'isChoice' => $this->isChoice));
        }

        return $messages;
    }

    /**
     * Scans a trans method call and returns found message.
     *
     * @throws ScannerException
     *
     * @param FunctionCallStatement $functionCall A FunctionCallStatement instance
     * @param array                 $message      An array to feed
     *
     * @return array Scanned message
     */
    private function scanFunctionCall(FunctionCallStatement $functionCall, array $message)
    {
        if (count($arguments = $functionCall->getArguments()) < 1) {
            throw new ScannerException('Method "'.$functionCall->getName().'" was called without argument', $functionCall->getLine());
        }

        $message['text'] = $this->parseString($arguments[0]);

        if (count($arguments) > $this->parametersIndex) {
            $message['parameters'] = $this->parseArrayStringKeys($arguments[$this->parametersIndex]);
        }

        if (count($arguments) > $this->domainIndex) {
            $message['domain'] = $this->parseString($arguments[$this->domainIndex]);
        }

        return $message;
    }
}
