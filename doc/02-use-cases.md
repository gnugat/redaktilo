# Use cases

Redaktilo has been created to meet some actual needs, which is why its
[Test Suite](../tests) contains an [Example](../tests/example) section.

Let's discover the use cases which led to the creation of Redaktilo:

* [YAML configuration edition](#yaml-configuration-edition)
* [JSON file edition](#json-file-edition)
* [PHP source code edition](#php-source-code-edition)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## YAML configuration edition

Many projects use the YAML format in their configuration files.

If you need to add a new parameter, you could use the
[Symfony2 YAML component](http://symfony.com/doc/current/components/yaml/index.html),
which is a small library allowing you to convert a YAML file to a PHP array and
vis-versa.

The only problem with it: it doesn't keep comments or empty lines.

Redaktilo fits perfectly for the job.

**Note**: see [BundleRoutingTest](../tests/example/BundleRoutingTest.php) for an
actual implementation.

## JSON file edition

Some projects use JSON files (like [Composer](https://getcomposer.org/)).

Just like with Symfony2 YAML component, you could use `json_encode` and
`json_decode` to edit these files, but you would lose empty lines and custom
indentation.

Redaktilo is again a good candidate.

## PHP source code edition

[GnugatWizardBundle](https://github.com/gnugat/GnugatWizardBundle) automatically
registers bundle installed using composer in a Symfony2 application.

To do so, it edits the `app/AppKernel.php` file using
[SensioGeneratorBundle](https://github.com/sensiolabs/SensioGeneratorBundle)'s
[KernelManipulator](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php).

This class is a little over engineered as it parses PHP tokens, and doesn't
allow to register bundle for a special environment, meaning that
GnugatWizardBundle will need to create its own KernelManipulator.

The result of a simpler approach (parsing lines instead of PHP tokens) is
Redaktilo!

**Note**: see [BundleRegistrationTest](../tests/example/BundleRegistrationTest.php)
for an actual implementation.

## Next readings

* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
* [Exceptions](06-exceptions.md)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
