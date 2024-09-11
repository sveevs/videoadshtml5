=== VideoADShtml5 ===
Contributors: -sv-
Donate link: https://sv-pt.ru
Tags: video, player, ads, pre-roll, vast
Requires at least: 5.3
Tested up to: 6.6.1
Stable tag: 2.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

VideoADShtml5 is a WordPress video Player where you can insert ads on your WordPress site.

== Description ==

[VideoADShtml5](https://sv-pt.ru/plugin-videoadshtml5-in-wordpress/) is a user-friendly WordPress video plugin to showcase your videos. You can embed both self-hosted videos or videos that are externally hosted using direct links and ads.

= Requirements =

= VideoADShtml5 Features =

* Embed MP4 video into your website
* Embed responsive video for a better user experience while viewing from a mobile device
* Embed HTML5 video which are compatible with all major browsers
* Embed video with autoplay
* Embed video with muted enabled


= VideoADShtml5Plugin Usage =

**Settings Configuration**

It's pretty easy to set up this video player plugin. Once you have installed the plugin simply navigate to the Settings menu where you will be able to configure some options. Mostly you just to need check the "Enable jQuery" option. That will allow the plugin to make use of jQuery library.

**Embedding Shortcodes for the Videos**

Now it's time to finally embed a video shortcode. To do this create a new post/page and use the following shortcode:


`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4"]`

**ADS at the beginning**

Now we use ads to display ads, at the beginning of the video clip

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" ads="http://example.com/wp-content/uploads/videos/reklama.mp4" ]`

**ADS at the end**

Embedding ads at the end of the video. Create a new post / page and use the following shortcode:

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" ads_end="http://example.com/wp-content/uploads/videos/reklama.mp4" ]`

**ADS at the beginning and end**

You can use two ads together in one video

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" ads="http://example.com/wp-content/uploads/videos/reklama.mp4" ads_end="http://example.com/wp-content/uploads/videos/reklama.mp4" ]`

Here, the URL is the shortcode parameter to replace with the actual URL of the video file, and the ADS parameter is responsible for the ad file.

**Video Autoplay**

If you want a particular video to start playing when the page loads, you can set the "autoplay" option to "true":

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" autoplay="true"]`

**Video Muted**

If you want the sound to be turned off, set the muted value "muted" option to "true":

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" muted="true"]`

**No more than 6 video clips**

If you want to add more than one video, enter in the format, but not more than 6

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" url1="http://example.com/wp-content/uploads/videos/myvid.mp4" url2="http://example.com/wp-content/uploads/videos/myvid.mp4" url3="http://example.com/wp-content/uploads/videos/myvid.mp4" url4="http://example.com/wp-content/uploads/videos/myvid.mp4" url5="http://example.com/wp-content/uploads/videos/myvid.mp4"]`

**Video Poster**

Poster placement using the poster tag

`[videoads url="http://example.com/wp-content/uploads/videos/myvid.mp4" poster="http://example.com/wp-content/uploads/pic/piction.jpg"]`

= Recommended Reading =

== Installation ==

1. Go to the Add New plugins screen in your WordPress Dashboard
1. Click the upload tab
1. Browse for the plugin file (videoadshtml5.zip) on your computer
1. Click "Install Now" and then hit the activate button
1. Now, go to the settings menu of the plugin and follow the instructions for embedding videos.

== Frequently Asked Questions ==

= Can this plugin be used to embed videos on my WordPress blog? =

Yes.

= Are the videos embedded by this plugin playable on mobile devices (iOS/Android)? =

Yes.

= Can I autoplay a video? =

Yes.

= Can I embed responsive videos using this plugin? =

Yes.

= Can I place ads?

Yes.

= How many ad blocks can I place?

Two blocks, at the beginning and at the end according to your desire.

= How many video clips can be inserted into the placement?

No more than 6.

== Screenshots ==
1. Preview of the VideoADShtml5 video player
2. Video ADS html 5 video Player settings in template
3. Example of use in adding material

== Changelog ==

= 2.5 =
* Jquery changes from jquery-3.5.1.js on jquery-3.7.1.js
* Edit inaccurate button display.
* Increasing the number of buttons to 8

= 2.4.1 =
* Error correction, poured into the logs

= 2.4 =
* Fixes for wordpress requirements. Edit color buttons.

= 2.3.6 =
* Adding a link to the rating and editing the button

= 2.3.5 =
* IP file errors

= 2.3.4 =
* Disabling e_notice and editing variables

= 2.3.3 =
* We remove the error with the recording of the file and the output of the link button when advertising

= 2.3.2 =
* Adding a link to the site and really unnecessary code

= 2.3.1 =
* Editing errors with the counter and content id

= 2.3 =
* Setting the active button on the video being viewed

= 2.2.7 =
* Setting the volume of the player in the admin panel

= 2.2.6 =
* Bug fix with 6 button

= 2.2.5 =
* Edit buttons in settings and from which version to download

= 2.2.4 =
* In the plugins section displays a link to the settings and the transition to the site

= 2.2.3 =
* A more beautiful view of the settings

= 2.2.2 =
* The ability to add and remove the download, picture-in-picture and playback speed buttons

= 2.2.1 =
* Ability to edit, upload or not in settings

= 2.2 =
* Ban on downloading videos from the player and edit the readme

= 2.1.3 =
* Edits to the number of variables

= 2.1.2 =
* Editing the placement of the poster on the preview screen

= 2.1.1 =
* Correction incorrect context menu output

= 2.1.0 =
* Enabling the context menu

= 2.0.2 =
* Fixed a bug, the same videos were shown on different devices at the same time

= 2.0.1 =
* Fixes the location of the buttons, for convenience

= 2.0.0 =
* Added work with 5 video files no more than

= 1.3.2 =
* Fixing errors with links

= 1.3.1 =
* Added information about the developer's site

= 1.3.0 =
* Added the ability to set how many seconds you can skip ads in the plugin settings

= 1.2.2 =
* Removed the scroll bar in the ad

= 1.2.1 =
* Editing a counter on a button

= 1.2.0 =
* Added a skip button that activates after 3 seconds

= 1.1.1 =
* Fixes for using 2 variables

= 1.1 =
* Added the ability to insert a second ad block at the end of the video

= 1.0.0 =
* Begin work 
