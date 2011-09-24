<?php

namespace BeSimple\RosettaBundle\Entity\Type;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Locale
{
    const GLUE = '_';

    /**
     * @var bool
     */
    private $useRegion;

    /**
     * @var string|null
     */
    private $language;

    /**
     * @var string|null
     */
    private $region;

    /**
     * @param string $locale
     * @param bool   $useRegion;
     */
    public function __construct($locale = '', $useRegion = true)
    {
        $this->useRegion = $useRegion;

        $this->set($locale);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->get() ?: '';
    }

    /**
     * @param $locale
     *
     * @return Locale This instance
     */
    public function set($locale)
    {
        $locale = $this->clean($locale);
        $parts  = explode(self::GLUE, $locale);

        $this->setLanguage($parts[0]);
        $this->setRegion(isset($parts[1]) ? $parts[1] : null);

        return $this;
    }

    /**
     * @return null|string
     */
    public function get()
    {
        if (is_null($this->language)) {
            return null;
        }

        return $this->language.($this->region ? self::GLUE.$this->region : '');
    }

    /**
     * @param string|null $inLocale
     *
     * @return null|string
     */
    public function getLanguageName($inLocale = null)
    {
        if (!$this->language) {
            return null;
        }

        if (!function_exists('locale_get_display_name')) {
            return $this->language;
        }

        $inLocale = $inLocale ?: (string) $this;

        return locale_get_display_name($this->get(), $inLocale);
    }

    /**
     * @param string|null $inLocale
     *
     * @return null|string
     */
    public function getRegionName($inLocale = null)
    {
        if (!$this->region) {
            return null;
        }

        if (!function_exists('locale_get_display_region')) {
            return $this->region;
        }

        $inLocale = $inLocale ?: (string) $this;

        return locale_get_display_region($this->get(), $inLocale);
    }

    /**
     * @return boolean
     */
    public function getUseRegion()
    {
        return $this->useRegion;
    }

    /**
     * @param null|string $language
     *
     * @return Locale This instance
     */
    public function setLanguage($language)
    {
        $this->language = $language ? strtolower($language) : null;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param null|string $region
     *
     * @return Locale This instance
     */
    public function setRegion($region)
    {
        $this->region = $this->useRegion ? ($region ? strtoupper($region) : null) : null;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @param string $locale
     *
     * @return string
     */
    private function clean($locale)
    {
        if (function_exists('locale_get_primary_language') && function_exists('locale_get_region')) {
            if (is_null($language = locale_get_primary_language($locale))) {
                throw new \InvalidArgumentException('Given locale "'.$locale.'" is invalid.');
            }

            $region = locale_get_region($locale);
            $locale = $language.($region ? self::GLUE.$region : '');
        }

        return $locale;
    }
}
