\_diff
======

###### \[Underscores Theme Diff\] — ***Tool to track changes of `_s` WordPress starter theme, the LESS version.***

The `_s`, [Underscores Starter Theme for WordPress](underscores.me) should not be used as parent theme. OK, but is there any good way to track changes (and eventually implement them back)?

Hence this project.

**\_diff** allows you to pull source files from `_s-LESS` folder into `underscores` destination folder. Such renamed destination template can be further put under version controll to track changes in batches and even to use as parent theme if you really need to. Haha \[evil laught\].

The LESS files are combined into one to ease reading and editing when pulled into single file. This is a default behaviour, but you can disable it by setting `$_diff->combineLESS = false;` in the `config.php`.

Only files in lowercase are included by default. To include all set `$_diff->skipALLCAPSFiles = false;` in `config.php`.

See comments in [_config.php](_config.php) for more options.

Enjoy!

[@martin_adamko](https://twitter.com/martin_adamko)

---


## 1. Installation

1. Download or clone [_s-LESS](https://github.com/mrpritchett/_s-LESS) theme and install it by copying/uploading to `/wp-content/themes`
1. Download and extract [zip of this project](./archive/develop.zip) or use `git clone` command
1. Upload `_diff` project directory next to the `_s-LESS` into `/wp-content/themes`
1. Navigate to `\_diff` in the browser, e.g. <http://www.example.com/wp-content/themes/\_diff>, the `config.php` should be created.
1. Edit `config.php`

## 2. Running

Navigate to `\_diff` in the browser, e.g. <http://www.example.com/wp-content/themes/\_diff>. The script copies the content of `_s-LESS` direcotory into destination.

LESS files are combined into **ONE** style.less file for easier tracking of chages.

**Warning: All destination files are overwritten by default.**