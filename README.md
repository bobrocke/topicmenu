# Grav Topic Menu Plugin

`topicmenu` is a simple [Grav](http://getgrav.org) plugin that traverses a portion of a website, typically `/blog`, and creates a two-dimensional array containing a first level taxonomy and each second level taxonomy associated with them.

Said another way, it returns an array like this: `topicmenu_array[level_1][level_2]`. If the `level_1` taxonomy was a blog category and the `level_2` taxonomy was the post tags, you’d have an array that listed all the tags that were associated with each category.

That array is then available to your Twig templates as the variable `topics_tags`.

# Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `topicmenu`. You can find these files on [GitHub](https://github.com/bobrocke/topicmenu).

You should now have all the plugin files under

    /your/site/grav/user/plugins/topicmenu

> > NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) to function

# Usage

The plug-in is enabled by default and will automatically start working. To configure or disable it, copy the `user/plugins/topicmenu/topicmenu.yaml` to `user/config/plugins/topicmenu.yaml` and make your modifications.

``` 
enabled: true                  # global enable/disable the entire plugin
page_page: /blog			   # set the starting path of the files to traverse
taxonomy_level_1: topic        # set the first level of the taxonomy to use
taxonomy_level_2: tag          # set the first level of the taxonomy to use
```