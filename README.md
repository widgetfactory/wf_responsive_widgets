# Responsive Widgets
A Joomla plugin for making object, video, audio and iframe elements responsive

## Download
Click [here to download](https://github.com/widgetfactory/wf_responsive_widgets/archive/master.zip), or the Download Zip button on the right.

## Installation
Install using the Joomla Extensions Installer

https://docs.joomla.org/Installing_an_extension

## What it does
This plugin adds a `wf-responsive` class to all object, embed, audio, video, and some iframe elements. For example, this code:

```html 
<video src="my_movie.mp4" type="video/mp4"></video>
```

will become:

```html
<video src="my_movie.mp4" type="video/mp4" class="wf-responsive"></video>
```

This class uses a few css rules to make the element responsive, ie: it resizes in proportion to the original dimensions when the page resizes or the page orientation changes. For iframes, a little bit of extra javascript is required to adjust the iframes height as the page resizes.

## How to use it
Once installed, remember to enable the plugin. Once enabled, all iframe, object, embed, video and audio tags will have a wf-responsive class added when the page loads.

To disable this for a particular element, add a class of `wf-no-container` or `wf-responsive-off` to the element, eg: 
```html
<video class="wf-responsive-no-container" src="video.mp4"></video>
```

## Bug Reports / Support / Issues
This plugin is in beta, so expect some probelms. Please use the Gitub Issue tracker to tell us about a any you've found.
