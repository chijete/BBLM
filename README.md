# BBLM: BitBook Lite Manuscript
BBLM is the markup language for BitBook Lite's manuscripts.

## TL;DR
BBLM is the markup language used in BitBook Lite scripts.
The `BBLM.php` file contains the code that allows you to translate BBLM to HTML and vice versa.

## File extension
BBLM official file extension is `.bblm` and mime type is `text/bblm`, although it can also be stored as plain text (`.txt` and `text/plain`).

## How to use
The class `BBLM` inside `BBLM.php` file has the following methods:
- `__construct` start an instance of the class.
- `BBLMtoHTML` translates a string in BBLM to HTML. Arguments:
	- `$base_string`: **(string)** BBLM input string.
	- `$delete_breaklines`: **(bool)** decides whether to remove line breaks from $base_string (\r\n).
	- `$e_htmlentities`: **(bool)** decides whether to apply PHP's htmlentities function to $base_string.
- `HTMLtoBBLM` translates a string in HTML to BBLM. Arguments:
	- `$base_string`: **(string)** HTML input string.
	 `$hard_conversion`: **(bool)**.
- `BBLMtoPlainText` translates a string in BBLM to plain text. Arguments:
	- `$base_string`: **(string)** BBLM input string.
	- `$delete_breaklines`: **(bool)** decides whether to remove line breaks from $base_string (\r\n).

## Examples
You can find use examples inside `test` folder.