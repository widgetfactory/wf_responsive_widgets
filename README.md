# Responsive Widgets
A Joomla plugin for making object, video, audio and iframe elements responsive

## Download
Click [here to download](https://github.com/widgetfactory/wf_responsive_widgets/archive/master.zip), or the Download Zip button on the right.

## Installation
Install using the Joomla Extensions Installer

## What it does
This plugin wraps all object, embed, audio, video, and some iframe elements in a div tag that contains a class that adds responsive features via css. For example, this code:

`<video src="my_movie.mp4" type="video/mp4"></video>`

will become:

`<div class="wf-video-container"><video src="my_movie.mp4" type="video/mp4"></video></div>`

Only iframe elements that display Youtube, Vimeo and DailyMotion content are wrapped in the div. On iOS (iPad, iPhone, iPod), iframes (not already wrapped in the responsive div) are wrapped in a div to fix a scolling issue on this platform.

## How to use it
Once installed, remember to enable the plugin. Once enabled, all iframe, object, embed, video and audio tags will be wrapped in a `<div>` container when the page loads.

To disable this for a particular element, add a class of "wf-no-container" to the element, eg: `<video class="wf-no-container" src="video.mp4"></video>`

Add {responsive=off} at the beginning of any article to disable the plugin for that article only.

## Bug Reports / Support / Issues
This plugin is in beta, so expect some probelms. Please use the Gitub Issue tracker to tell us about a any you've found.
