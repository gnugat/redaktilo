# How to contribute

Everybody should be able to help. Here's how you can make this project more
awesome:

1. [Fork it](https://github.com/gnugat/redaktilo/fork_select)
2. improve it
3. submit a [pull request](https://help.github.com/articles/creating-a-pull-request)

Your work will then be reviewed as soon as possible (suggestions about some
changes, improvements or alternatives may be given).

Here's some tips to make you the best contributor ever:

* [Green tests](#green-tests)
* [Standard code](#standard-code)
* [Specifications](#specifications)
* [Use cases](#use-cases)
* [Keeping your fork up-to-date](#keeping-your-fork-up-to-date)

## Green tests

Run the tests using the following script:

```console
$ bin/tester.sh
```

## Standard code

Use [PHP CS fixer](http://cs.sensiolabs.org/) to make your code compliant with
Redaktilo's coding standards:

```console
$ php-cs-fixer fix --config=sf23 .
```

## Specifications

Redaktilo drives its development using [phpspec](http://www.phpspec.net/).

First boostrap the code for the Specification:

```console
$ phpspec describe 'Gnugat\Redaktilo\MyNewClass'
```

Then write the code for the Specification:

```console
$ $EDITOR spec/Gnugat/Redaktilo/MyNewClass.php
```

Next, bootstrap the code for the corresponding clas:

```console
$ phpspec run
```

Follow that by writing the code of the corresponding class:

```console
$ $EDITOR src/Gnugat/Redaktilo/MyNewClass.php
```

Finally, execute the specifications:

```
$ phpspec run
```

They should be green!

## Use cases

Redaktilo has been created to fulfill actual needs. To keep sure of it, use
cases are created and are automated: they become part of the test suite.

Have a look at `tests/examples`, you might add your own.

## Keeping your fork up-to-date

To keep your fork up-to-date, you should track the upstream (original) one
using the following command:

```console
$ git remote add upstream https://github.com/gnugat/redaktilo.git
```

Then get the upstream changes:

```console
git checkout main
git pull --rebase origin main
git pull --rebase upstream main
git checkout <your-branch>
git rebase main
```

Finally, publish your changes:

```console
$ git push -f origin <your-branch>
```

Your pull request will be automatically updated.
