A Symfony2 that ease translation process
========================================

RosettaBundle is inspired by the great eponymous Django app : [Rosetta](http://code.google.com/p/django-rosetta/).
It consists in:

-  Scaning project source files to retrieve a strings to translate
-  Live translation scan if your strings are setted in $variables or database
-  Helping translation with use of the [Google AJAX Language API](http://code.google.com/apis/ajaxlanguage/)
-  Import existing translations files in the Rosetta system
-  Offering an admin application to manage translations

**Developpement in progress, come back later for use**


Install the bundle
------------------


1.  Fetch the source code
2.  Add the bundle to your `AppKernel` class
3.  Setup the bundle in your config file
4.  Make sure Doctrine ORM is enabled and configure it


Available settings
------------------


-  `rosetta.config`:   global configuration
-  `rosetta.scanners`: scanners classes
-  `rosetta.live`:     enable live system
-  `rosetta.admin`:    admin configuration



Available commands
------------------


-  `rosetta:scan`:      scan your project and store strings in database
-  `rosetta:import`:    import existings translation files to database
-  `rosetta:translate`: auto translate untranslated strings from database
-  `rosetta:deploy`:    deploy translations to Symfony translation files
-  `rosetta:admin`:     generate the admin interface