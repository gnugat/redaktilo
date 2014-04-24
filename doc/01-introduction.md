# Introduction

Have you ever wanted your code to be able to add a new line in a file?

That's what Redaktilo is there for!

## What's the point?

I've encountered many times the need to edit files programmatically, here's some
of my use cases.

### Symfony2 Yaml dumper replacement

The [Symfony2 Yaml component](http://symfony.com/doc/current/components/yaml/index.html)
is a small library which can be used without the whole framework.

It allows you to convert a yaml file to a PHP array and vis-versa. The only
problem with it: it doesn't keep comments or empty lines.

In most cases I just want to add a line in a yaml configuration file which
contain some usefull comments. Redaktilo fits perfectly for the job here!

### composer.json edition

[Composer](https://getcomposer.org/) is a dependency manager for PHP which uses
`composer.json` as configuration file.

While the console tool provides some commands to edit it (like `require` for
example) I some time still need to edit it, for example if I want to add a
script.

Redaktilo allows me to create installers in PHP which will be able to update
this section.

### Symfony2 bundle registration

Symfony2 has a [large community which provides bundles for every needs](http://knpbundles.com/).
However installing a bundle requires the developer to manually add a line in the
`app/AppKernel.php` file.

I created a composer plugin which will automatically do this task, using
Redaktilo.

## Next readings

* [installation](02-installation.md)
* [usage](03-usage.md)
* [tests](04-tests.md)
