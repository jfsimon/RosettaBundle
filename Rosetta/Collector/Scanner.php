<?php

namespace BeSimple\RosettaBundle\Rosetta\Collector;

use Symfony\Component\Finder\Finder;
use BeSimple\RosettaBundle\Rosetta\Workflow\Input;
use BeSimple\RosettaBundle\Rosetta\Workflow\InputCollection;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Scanner extends AbstractCollector
{
    /**
     * Scans a bundle for translation messages.
     *
     * @param string      $bundle A bundle name
     * @param string|null $domain A domain name
     * @param string|null $locale A locale
     *
     * @return Scanner This instance
     */
    public function scanBundle($bundle, $domain = null)
    {
        $files = Finder::create()
            ->files()
            ->in($this->locator->getBundlePath($bundle));

        foreach ($this->factory->getScannerAliases() as $alias) {
            $files->name('*.'.$alias);
        }

        foreach ($files as $file) {
            $inputs = $this->collectInputs((string) $file, $bundle);

            if (is_null($domain)) {
                $this->inputs->merge($inputs);
            } else {
                foreach ($inputs->all() as $input) {
                    if ($input->getDomain() === $domain) {
                        $this->add($input);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Scans a file for translation messages.
     *
     * @param string $file A filename
     *
     * @return Scanner This instance
     */
    public function scanFile($file)
    {
        $bundle = $this->locator->guessBundleName($file);
        $inputs = $this->collectInputs($file, $bundle);

        $this->inputs->merge($inputs);

        return $this;
    }

    /**
     * @param string $file   A filename
     * @param string $bundle A bundle name
     * @return InputCollection
     */
    private function collectInputs($file, $bundle)
    {
        $inputs = new InputCollection();
        $alias  = substr($file, strrpos($file, '.') + 1);

        if (!in_array($alias, $this->factory->getScannerAliases())) {
            return $inputs;
        }

        foreach ($this->factory->getScanner($alias)->scan($file) as $message) {
            $bundle = $bundle ?: $this->locator->guessBundleName($file);
            $input  = new Input($bundle, $message['domain'], $message['text'], $message['parameters']);

            $inputs->add($input);
        }

        return $inputs;
    }
}
