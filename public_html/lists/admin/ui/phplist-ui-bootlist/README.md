# BootList
A bootstrap-based theme for [phpList 3](https://github.com/phpList/phplist3/)

[![Build Status](https://travis-ci.org/phpList/phplist-ui-bootlist.svg?branch=master)](https://travis-ci.org/phpList/phplist-ui-bootlist)

![alt tag](http://thumbnails117.imagebam.com/52193/c1cd67521924237.jpg)
![alt tag](http://thumbnails117.imagebam.com/52193/063f75521924240.jpg)

Bootstrap class names and extra html needed, inserted by jQuery with the script:
`js/phpList3ToBootstrap.js`

## Issues

* [View issues](https://mantis.phplist.org/search.php?project_id=2&category=Theme%20-%20bootstrap&sticky_issues=off&sortby=last_updated&dir=DESC&hide_status_id=-2&match_type=0) on Mantis issue tracker
* [Report issues](https://mantis.phplist.org/bug_report_page.php) on Mantis issue tracker (use category *Theme - bootstrap*)

## Getting started
Building the project requires nodejs & npm. See https://nodejs.org for setup.

### Install Grunt globally
``` 
sudo npm install -g grunt-cli
```
### Grunt plugins
To minify js we use this grunt plugins:

https://github.com/gruntjs/grunt-contrib-concat

https://github.com/gruntjs/grunt-contrib-uglify


### Install project dependencies
``` 
cd THEME_DIR # Replace THEME_DIR by theme path.
sudo npm install
```
### Watch the project
``` 
grunt watch
```
Each time a less file is changed, style.css will be generated automatically.
