<?php

namespace BeSimple\RosettaBundle\Rosetta\Collector;

use Symfony\Component\Finder\Finder;
use BeSimple\RosettaBundle\Rosetta\Workflow\Input;
use BeSimple\RosettaBundle\Rosetta\Workflow\InputCollection;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Importer extends AbstractCollector
{
    private $files = array();

    /**
     * Imports all translation files from a bundle.
     *
     * @param string      $bundle A bundle name
     * @param string|null $domain A domain name
     * @param string|null $locale A locale
     *
     * @return Importer This instance
     */
    public function importBundle($bundle, $domain = null, $locale = null)
    {
        $files = Finder::create()
            ->files()
            ->name(sprintf('/%s\\.%s\\.[a-z]+$/i', $domain ?: '[a-z]+', $locale ?: '[a-z]+'))
            ->in($this->locator->getBundlePath($bundle).DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'translations');

        foreach ($files as $file) {
            $this->inputs->merge($this->collectInputs($file, $bundle));
        }

        return $this;
    }

    /**
     * Imports messages from a translation file.
     *
     * @param string $file A filename
     *
     * @return Importer This instance
     */
    public function importFile($file)
    {
        $bundle = $this->locator->guessBundleName($file);
        $inputs = $this->collectInputs($file, $bundle);

        $this->inputs->merge($inputs);

        return $this;
    }

    /**
     * Fetches imported files.
     *
     * @return array
     */
    public function fetchFiles()
    {
        $files = $this->files;
        $this->files = array();

        return $files;
    }

    /**
     * @param string $file   A filename
     * @param string $bundle A bundle name
     * @return InputCollection
     */
    private function collectInputs($file, $bundle)
    {
        $this->files[] = $file;
        $inputs = new InputCollection();
        list($domain, $locale, $format) = explode('.', basename($file));

        foreach ($this->factory->getLoader($format)->load($file, $locale)->all() as $messages) {
            foreach ($messages as $text => $translation) {
                $parameters = array();

                foreach ($this->factory->getParametersGuesser() as $guesser) {
                    $parameters = array_merge($guesser->validate($translation, $guesser->guess($text)));
                }

                $input = new Input($bundle, $domain, $text, $parameters);
                $input->addTranslation($locale, $translation);

                $inputs->add($input);
            }
        }

        return $inputs;
    }
}
