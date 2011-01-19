A Symfony2 that ease translation process
========================================


**Developpement in progress, come back later for use**


RosettaBundle is inspired by the great eponymous Django app : [Rosetta](http://code.google.com/p/django-rosetta/).
The main features :

-  Scan your project source files to retrieve messages to translate
-  Auto-translate messages with [Google AJAX Language API](http://code.google.com/apis/ajaxlanguage/)
-  Live scan/auto-translation (if your strings are setted in variables or database)
-  Import existing translations files in the Rosetta system
-  And at last but not least offers an admin interface for manual translation


Install & setup the bundle
--------------------------


1.  Fetch the source code

    Using Git to constrol your project from project root directory:
    
        git dubmodule add git://github.com/jfsimon/RosettaBundle.git
        
    Just clonind repository (in `src/Bundle`):
    
        git clone git://github.com/jfsimon/RosettaBundle.git

2.  Add the bundle to your `AppKernel` class

        \\ app/AppKernerl.php
        public function registerBundles()
        {
            // ...
            new Bundle\RosettaBundle\RosettaBundle()
            // ...
        }

3.  Setup the bundle in your config file
    
    An exemple in Yaml format:
    
        rosetta.config:
            store:     true
            translate: true
            
        rosetta.scanners:
            php:  Bundle\RosettaBundle\Scanner\PhpScanner
            twig: Bundle\RosettaBundle\Scanner\TwigScanner
            
        rosetta.live:
            store:     true
            translate: true
            choose:    true

4.  Make sure Doctrine ORM is enabled and configure it (dont forget DBAL)

    An exemple in Yaml format:
    
        doctrine.orm:
            rosetta:
                path:
                
5.  Enable admin routing

    An exemple for your `app/routing.yml`:
    
        rosetta.admin:
            resource: Bundle/RosettaBundle/Resources/routing/admin.yml
            prefix:   _rosetta/adlin
        rosetta.ajax:
            resource: Bundle/RosettaBundle/Resources/routing/ajax.yml
            prefix:   _rosetta/ajax
        rosetta.preview:
            resource: Bundle/RosettaBundle/Resources/routing/preview.yml
            prefix:   _rosetta/preview


Available settings
------------------


**Will come later**


Available commands
------------------


-  `rosetta:scan`:      scan your translation messages and store strings in database

   -  `:file`:          scans a file
   -  `:bundle`:        scans a bundle
   -  `:project`:       scans your whole project
   
-  `rosetta:import`:    import existings translation files to database

   -  `:domain`:        import a domain (within a bundle)
   -  `:bundle`:        import all domains from a bundle
   -  `:project`:       import whole project
   
-  `rosetta:translate`: auto translate untranslated strings from database

   -  `string`:         translate given string using [Google AJAX Language API](http://code.google.com/apis/ajaxlanguage/)
   
-  `rosetta:deploy`:    deploy translations to Symfony translation files in given format

   -  `:domain`:        deploy a domain (within a bundle)
   -  `:bundle`:        deploy all domains from a bundle
   -  `:project`:       deploy whole project