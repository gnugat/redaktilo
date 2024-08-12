# Changes between versions

## 2.0.0: Symfony 5 / PHP 7.2

* bumped requirement to Symfony 5
* bumped requirement to PHP 7.2

## 1.7.4: PHP 8

* added support for PHP 8

## 1.7.3: Symfony 4

* added support for Symfony 4

## 1.7.2: No Final

* removed final

## 1.7.1: Updated dependencies

* added support to Symfony 3
* added support to PHP 7
* updated to phpspec 2.4
* updated to PHPUnit 4.5
* added support to PHPUnit 5

## 1.7.0: Brace yourselves, v2 is coming

* added documentation to upgrade to 2.0
* added Text#map
* added deprecation messages

## 1.6.0: Text construction

* added an optional filename argument to Editor#save
* removed TextFactory
* removed Text#__construct
* removed LineBreak
* added Text::fromArray
* added Text::fromString
* added StringUtil::detectLineBreak

## 1.5.0: Current line number Incrementation

* added Text#decrementCurrentLineNumber
* added Text#incrementCurrentLineNumber

## 1.4.0: Exception

* added PatternNotFoundException
* added NotSupportedException
* added InvalidLineNumberException
* added InvalidArgumentException
* added IOException
* added FileNotFoundException
* added DifferentLineBreaksFoundException
* added CommandNotFoundException
* added Exception

## 1.3.0: Chicken Run

* added ContentFactory
* added LineReplaceAllCommand
* added Editor#run

## 1.2.1: Fixed Backward Compatibility Break

* fixed BC break by making command constructor arguments optional

## 1.2.0: InputSanitizers

* added mix of line break management
* fixed text first line getter setter
* fixed LineRemoveCommand using array_splice
* added LocationSanitizer
* added TextSanitizer
* added InputSanitizer interface

## 1.1.6: Applying fix for line removal

* fixed line number after line removal

## 1.1.5: Fixed remove command

* fixed line numbers after line removal

## 1.1.4: Fixed Text InvalidLineNumberException

* added tests for search relative to the first line
* added tests for line search with the immediate line above
* fixed Text to actually throw InvalidLineNumberException

## 1.1.3: Fixed line search above

* fixed LineSearchStrategy#findAbove

## 1.1.2: Fixed locations

* fixed passing of 0 as location

## 1.1.1: Fixed exceptions

* fixed order of arguments in PatternNotFoundException
* fixed message in NotSupportedException and PatternNotFoundException

## 1.1.0: Convenience

* deprecated string support from LineReplaceCommand
* deprecated has from Editor
* added callback support to LineReplaceCommand
* added hasAbove and hasBelow to Editor
* added loggable exceptions
* added line getter and setter to Text
* added priority to Search Strategies

## 1.0.0: Documentation

* updated documentation

## 1.0.0-rc3: Length

* added Text length

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
