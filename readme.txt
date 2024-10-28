=== 5sterrenspecialist ===
Contributors: 5sterrenspecialist
Tags: 5sterrenspecialist, 5-sterrenspecialist, rich, snippets
Requires at least: 4.6.0
Tested up to: 5.8.1
Stable tag: 1.2
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is made by 5sterrenspecialist.nl in order to provide rich snippets for our clients.

== Description ==
This plugin is made by 5sterrenspecialist.nl in order to provide rich snippets for our clients.

== Installation ==
For installation instructions, see:
https://www.5sterrenspecialist.nl/paginas/40-google-rich-snippets-plugin-handleiding.html

== Frequently Asked Questions ==
Geen css functie?

Mocht uw thema geen ‘custom css overschrijf’ functie niet hebben, dan zijn er ook plugins die deze functie aanbieden:
https://nl.wordpress.org/plugins/simple-custom-css/

https://nl.wordpress.org/plugins/custom-css-js/
Daarnaast blijft het mogelijk om deze css opmaakcodes direct in de styleseets van de website te verwerken.
De widget toont meer dan 5 sterren?
Standaard is het blok met de 5 sterren (div met class ‘rating-box’) 120 pixels breed en zijn de sterren 24 pixels hoog. Wanneer de sterren worden verkleind, dan passen er meer sterren in het blok. Om dit op te lossen kan de div ‘rating-box’ ook worden verkleind.
De widget staat op een aparte/nieuwe regel (enter)

Wilt u dat de widget bijvoorbeeld achter een regel tekst staat, maar wordt deze eronder geplaats (terwijl er wel ruimte voor is)? Pas dan de volgende code in de css aan:
.snippet-5sterrenspecialist {
    display: block;

Naar:
.snippet-5sterrenspecialist {
    display: inline-block;

== Changelog ==
1.2: Added CompanyName to the rich snippet code for new AggregateRating requirements.
1.1: Updated and tested against 4.9.6
1.0: Final version for 5-sterrenspecialist clients

== Upgrade Notice ==
The latest version for max stability