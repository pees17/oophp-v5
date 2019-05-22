Demo av klassen TextFilter
==========================

Detta är en demo av klassen TextFilter. Klassen innehåller 5 publika metoder. Fyra metoder som var och en gör en viss typ av filtrering av en text, samt en metod som är den som är själva API:t till klassen. Denna metod heter parse() och tar som argument den text som ska filtreras samt en array med de olika filter som ska appliceras på texten innan den returneras.

Följande filtreringar kan göras:

* BBCode formatting converting to HTML
* Make clickable links from URLs in text
* Format text according to Markdown syntax
* nl2br formatting of text

För att se effekten av dessa 4 filtreringar så klicka på respektive länk nedan. Varje länk visar den oformatterade texten, den formatterade texten (med html-taggar), samt den formatterade texten som den ser ut i en webbläsare.

* [Test bbcode2html](textfilter/bbcode)
* [Test clickable](textfilter/clickable)
* [Test Markdown](textfilter/markdown)
* [Test nl2br](textfilter/nl2br)
