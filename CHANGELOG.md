# Changes between versions

## 1.0.0-rc2: Composer improvements

* lowered requirements
* fixed PSR4
* added keywords
* added @pyrech to authors

## 1.0.0-rc1: Safe checks

* added totalLineNumber to Text
* added safe checks

## 1.0.0-beta2: Fixed last BC breaks

* renamed under with below
* removed File prefix from Editor's open/save

## 1.0.0-beta1: Stable API

* moved FileFactory into Filesystem
* removed newText from Editor

## 1.0.0-alpha7: New src layout

* Updated EditorBuilder with extensible only services
* Removed api tag from services
* Moved EditorBuilder into Service
* Moved Filesystem into Service
* Moved TextFactory into Service
* Moved FileFactory into Service
* Moved TextToPhpConverter into Service
* Renamed PhpContentConverter to TextToPhpConverter
* Removed ContentConverter interface

## 1.0.0-alpha6: Text and line content

* renamed LineContentConverter to LineBreak
* used Text instead of File
* removed constructor API
* removed File read and write
* injected lines instead of content in File
* used FileFactory in Filesystem
* created FileFactory
* made File a Text
* suffixed Editor open/save with "File"
* added newText in Editor
* created TextFactory
* created Text

## 1.0.0-alpha5: BC break renaming

* renamed `SearchStrategy` `previous`/`next` to `above`/`under`
* renamed `Editor` `addBefore`/`addAfter` to `insertAbove`/`insertUnder`
* renamed `Editor` `changeTo` to `rename`
* renamed `Editor` `jumpUpTo`/`jumpDownTo` to `jumpAbove`/`jumpUnder`
* improved the vocabulary with `Actions`, `Directions` and `Location`

## 1.0.0-alpha4: Fix current line

* fixed current line update's responsability in commands

## 1.0.0-alpha3: Deprecations

* removed `changeTo` from `File`

## 1.0.0-alpha2: Quality

* fixed Insight analysis 15

## 1.0.0-alpha1: Commands, boolean finds and locations

* removed `SubstringSearchStrategy`
* removed `replaceWith` from `Editor`
* added `LineInsertAboveCommand`
* added `LineInsertUnderCommand`
* removed `LineInsertCommand`
* moved line management from `Editor` to commands
* renamed `UnsupportedCommandException` into `CommandNotFoundException`
* added before/after to the `Editor` jumpTo methods
* removed `FactoryMethod`
* removed `SearchStrategy`'s `has` method
* added before/after to the `SearchStrategy` find methods
* added abstract class `LineSearchStrategy`
* renamed `LineSearchStrategy` to `SameSearchStrategy`
* moved search exception throwing from `Search` to the `Editor`
* added location for `Editor`'s manipulation methods
* moved `NotSupportedException` from `Engine` to `Search`
* replaced `ReplaceEngine` with `CommandInvoker`
* removed `ReplaceStrategy`
* added `InsertCommand`
* added `RemoveCommand`
* added `ReplaceCommand`

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
