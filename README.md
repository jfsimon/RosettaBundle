A Symfony2 that ease translation process
========================================


**Warning!** Full refactoring started, needed by Symfony2 updates.


RosettaBundle is inspired by the great Django app : [Rosetta](http://code.google.com/p/django-rosetta/).


Taml configuration example
--------------------------

    besimple_rosetta:
        database:       orm
        live:           false
        translator:     google
        deployer:       yaml
        tasks:
            default:    [load, save]
            scanner:    [load, translate, save]
            importer:   [load, choose, save]
        rating:
            translator: 1
            importer:   5
        keys:
            google:     YOUR_KEY_HERE
