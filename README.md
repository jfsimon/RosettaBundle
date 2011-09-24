The Symfony2 i18n bundle
========================


RosettaBundle brings you many tools to collect & translate your i18n messages: a set of interactive commands,
a customizable workflow, a smart web-profiler panel *(to come)* and a complete admin interface *(to come too)*.


How it works
------------


**Principle:**

-   Collect messages
    -   Scan PHP & Twig files
    -   Import translation files
-   Translate messages
    -   With web-services or manually through commands & interfaces
    -   You can affect many translation to a message, rate them & select one
-   Dump translations
    -   Standard formats supported: Xliff, Yaml, PHP (and CSV)
    -   Only selected translations are dumped


**Storage:**

-   Uses Doctrine ORM
-   3 entities model:
    -   Group: stores the bundle & domain
    -   Message: stores text, parameters and 2 booleans: isChoice & isKey
    -   Translation: stores locale, text, rating and isSelected boolean
-   Locale field is managed by a custom type


**Customization:**

-   All collected messages are sent into the workflow system
-   You can build custom tasks to apply treatments
-   3 tasks are provided:
    -   Auto-translation (via a webservice)
    -   Auto-selection (highest rated translation is selected)
    -   Auto-dumping (selected translations are dumped into trasnaltion file)


Status
------


**The low level API is complete and tested:**

-   PHP and Twig files scanners
-   Translation files import and parameters guesser
-   Auto-translation webservices bindings (Bing, Google translate & myGengo)
-   Translation files dumpers (Xliff, Yaml, PHP & CSV)

*Translation webservices are not unit tested because of the API keys and the need of an account.*

RosettaBundle comes with 3 test commands (import, scan & translate) witch allow to test your configuration
by displaying operations result in a nice way.

**Warning:** scanning some Twig templates throws error because of services not started (for example, Request in a
command context). Twig scanner needs a full refactoring.


**The core API is complete and partially tested:**

-   Backup service done & tested
-   Locator service done & tested
-   Workflow commes with 3 tasks:
    -   Translate: use a webservice to auto-translate messages
    -   Select: selects highest rated translation
    -   Dump: dumps selected translations into i18n files (xliff by default)

*Workflow tasks are not currently tested.*


**Commands in progress:**

-   Test commands work well
-   Collector commands needs refactoring
-   More commands to come


**Planned interfaces:**

-   Web-profiler panel
-   Admin interface


Resources
---------


**Documentation:**

All doc files are stored in `Resources/doc` folder.


**License:**

See `Resources/meta/LICENSE` file.
