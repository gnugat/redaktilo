# Changes between versions

## 0.9.0: PhpSearchStrategy

* added `PhpSearchStrategy`
* moved `SearchEngine` from the `Engine` namespace to the `Search` namespace
* moved `ReplaceEngine` from the `Engine` namespace to the `Replace` namespace

## 0.8.0: SearchStrategy and EditorFactory

* removed `StaticContainer` in favor of `EditorBuilder` and `EditorFactory`
* added `SubstringSearchStrategy`
* fixed `tester.sh` exit status
* added `LineRegexSearchStrategy`

## 0.7.2: Fixed routing test

* fixed the routing test by removing the dependency on sf2's DI component

## 0.7.1: Fixed line break

* fixed line break in line content converter's back method

## 0.7.0: ContentConverter

* replaced introduction with tutorial
* replaced architecture details with reference
* added vocabulary (cursor and line)
* added static DIC documentation
* added factory methods documentation
* added `BundleRouting` example
* fixed `Editor::addAfter` by moving down the cursor
* added `Line` factory method for empty ones
* added `Filesystem` factory method to force creation
* moved factory methods into `Gnugat\Redaktilo`
* removed `Filesystem`'s `detectLineBreak` method
* removed `File`'s `readlines` and `writelines` methods
* injected `LineContentConverter` into:
  + `LineReplaceStrategy`
  + `LineSearchStrategy`
  + `LineNumberSearchStrategy`
* added `LineContentConverter`
* added `ContentConverter`

## 0.6.1: Fixed DIC

* fixed private methods into public static ones

## 0.6.0: ReplaceEngine

* moved Engines into thei own directory
* added `ReplaceEngine` to comply to the open/closed principle
* removed `File`'s `hasLine`
* added a Dependency Injection Container
* added a use case for line presence checking
* added a use case for "documentation reformatting"
* replaced Behat by PHPUnit for automated use cases

## 0.5.0: SearchEngine

* added `SearchEngine` to comply to the open/closed principle

## 0.4.0: Line manipulations

* added line replacement
* added line removal
* added checking of line presence

## 0.3.0: File coming out

* added file existence check
* added line break detection
* added `bin/tester.sh` script
* moved stateness from `Editor` to `File`
* moved classes at the root
* removed interfaces
* compiled documentation
* improved tests

## 0.2.0: Jump to

* added jump to methods to Editor
* removed autosave
* added manual save
* added usage documentation
* added use cases documentation
* removed installer

## 0.1.2: Fix open

* fixed `openFile` to `open`

## 0.1.1: Continuous Integration

* fixed Insight analysis 1
* added travis configuration
* added badges on README

## 0.1.0: Initial release

* created file opening
* created insertion of line after a given one
