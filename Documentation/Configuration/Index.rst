.. include:: ../Includes.txt

.. _configuration:

=============
Configuration
=============

After installing the extension some small steps are needed to make everything work like expected.
The extension comes currently with a connection to the google translator. This one is prebuilt and could be used out of
the box. If you want to use another translation service like the Microsoft Azure Translator you should have a look at
the developer documentation: `Developer documentation <Documentation/Developer/Index.rst>`__

Creating a translation service
==============================
Use the "List"-Module and navigate in the page tree to the root page. Create a new record and select the Translation service
within the "T3 Translator" group and fill all fields with the required values.
For the google translator you would need only an API-Key from your google cloud backend.

Connect the service with a language
===================================
When you have created the translation service you need to connect an alternative page language with the translation service.
This must be done within the "Website Language" record which should be connected with the translation service. In this
record you have a new select-dropdown with the newly created translation service as possible value.
Through this implementation is it possible to use different translation services for different languages.
