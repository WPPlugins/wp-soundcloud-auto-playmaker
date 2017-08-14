=== Soundcloud Auto Playmaker ===
Contributors: fergalmoran 
Tags: soundcloud, playlist, player, podcast, xml, ajax
Requires at least: 2.6
Tested up to: 3.0 RC3
Stable tag: 0.8

Automatically creates soundcloud players, given  a soundcloud user or group or single track, for inserting into your posts or into a widget.


== Description ==
Creates soundcloud players to be inserted in a widget or in posts. Give it the nane of a user or group and it will generate players for their tracks or their favourites and embed them in your site. 
Take it for a test drive and see...

Not quite finished yet known issues are.
Ajax paging won't work with 2 widgets on the same page
Group must be entered by id, not name (blame soundcloud, not me..)
Post embedding has not yet been implemented

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

Global settings which will effect all players can be set in Appearence/Soundcloud Playmaker 

Drag a widget into one of your sidebars and you can customise that individual widget there.

Post embedding is WIP at the moment.
== Changelog ==

== 0.8 ==
* Feature: Color setting is now being read by widget
* Feature: Choices for show profile & show track links are being read
* Feature: Added template files into includes/templates for further customisation

== 0.7.1 ==
* Minor: Changed version number

= 0.6.6.2 =
* Minor: Removed message about invalid header

= 0.6.6.1 =
* Minor: Removed erroneous echo in admin headers

= 0.6.6 =
* Minor: Fixed bug causing plugin to show up twice in installed plugins
* Minor: Refactored come classes

= 0.6.5 =
* Minor: Update to readme

= 0.6.4 =
*  Minor: Refactored some javacript and sc_SoundCloudWidgetAdmin class

= 0.6.2 =
* Minor: Fixed loading of JS and CSS

= 0.6 =
* Feature: Added admin panel in Appearance menu to allow global options such as player appearence to be set.
* Important: Fixed jQuery issue which broke other admin panels
* Minor: Fixes to the Soundcloud user profile link in widget
