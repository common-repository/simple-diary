=== Simple Diary for Wordpress ===
Contributors: jojaba
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=5PXUPNR78J2YW&lc=FR&item_name=Jojaba&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: diary, reminders, custom post type
Requires at least: 3.8
Tested up to: 5.3
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very simple diary listing user created reminders. Ready to use in WordPress default themes.

== Description ==

Simple Diary is meant to be simple enough to be used out of the box. Simple, but also powerfull and customizable. All skill user should find something to do with it.

Here's the list of the settings (see screenshots for further infos):

* Custom post type "reminder" available. The reminder infos : Title, start date (required), end date (optional), start time(optional), end time (optional), location (required), url (optionnal), article (optional). The date and time infos are set using [pickadate.js](http://amsul.ca/pickadate.js/ "Go to the pickadate.js homepage") jQuery plugin. All the system (compose reminder page and datepicker) is responsive.
* The admin reminders edit page is sorted by start date and contain title, start date, end date, location and creation/modification date. All columns are sortable except location column.
* Option page will let you modify some settings : Title of the diary page, slug modification, reminder count listed in upcoming reminders, reminder count in diary, selection of the columns in edit page.
* Possibility to add a reminder from a post (beginning with 1.3 plugin version)
* All default WordPress themes (twentyten, twentyeleven, twentytwelve, twentythirteen, twentyfourteen) can easily be updated to take in account the reminders. You just have to get archive-reminder.php, content-reminder.php or loop-reminder.php, single-reminder.php from `/simple-diary/themes-support/your_theme/` and put it into your hteme folder (`/wp-content/themes/your-theme/`). You can take these files also as examples to customize Diary and reminders for your theme.
* A "Upcoming reminders" widget is available in the admin widget section.

Simple Diary has been developed by keeping in mind following rules:

* Easy to install, use and customize
* Working on every theme (including responsive themes)
* Adding microdata used to markup HTML code for semantic (so that most popular search providers can handle the infos).
* Make it translatable (availabe languages : english and french).

== Installation ==

= 1-The plugin installation =

1. Upload the `simple-diary` directory to `/wp-content/plugins/` folder of your Wordpress installation
2. Activate the plugin through the 'Plugins' menu in WordPress

Of course if you'd like to add it using the WordPress plugin manager, you can do it, it's the easiest way ;)

= 2-The theme update =

1. Find what theme you use in your administration Appearance » Theme section
2. Upload the 3 files (archive-reminder.php, content-reminder.php or loop-reminder.php, single-reminder.php) matching your theme into the `/wp-content/themes/your_theme/` folder. You can find these files in `/simple-diary/themes-support/your_theme/` folder. For example, if you use twentyfourteen theme, you have to get the 3 files in `/simple-diary/themes-support/twentyfourteen/` folder and upload them into the `/wp-content/themes/twentyfourteen/` folder.

== Frequently Asked Questions ==

= Where could I find the template functions of Simple Diary? =

Edit the `/simple-diary/simdiaw-template-functions.php` file, you will find all available template functions.

= I don't want to use the widget to display the upcoming reminders, is it possible? =

Yes, you can list the upcoming reminders everywhere you want, you just have to use the `the_simdiaw_upcoming_reminders()` function to get them in list format.

This code:
`&lt;ul&gt;`
   `&lt;?php the_simdiaw_upcoming_reminders(2) ?&gt;`
`&lt;/ul&gt;`
Will generate a html code like this:
`&lt;ul&gt;`
  `&lt;li&gt;Eiffel tower visiting&lt;br&gt;Date: 30/06/2014&lt;br&gt;Location: Paris&lt;/li&gt;`
  `&lt;li&gt;Storks observation&lt;br&gt;Date: 06/06/2014&lt;br&gt;Location: Obersoultzbach&lt;/li&gt;`
`&lt;/ul&gt;`

= Is this plugin compatible with Gutenberg, the new WordPress Editor? =

Yes. Beginning with 1.3 version of this plugin.

= Can I duplicate some recurrent events, reminders? =

No, sorry! But you can alternatively use the [Duplicate Post](https://fr.wordpress.org/plugins/duplicate-post/) plugin developped by [Enrico Battocchi](https://lopo.it/). I tested it and it worked fine with reminders. You just have to go to the settings of Duplicate Post and enable the custom post _reminder_ to have it work for it.

= Can I create a reminder from a post compose window? =
Yes, you can! You just have to enable this feature in the option panel, after that, you will be able to create a reminder using the link located in the Sidebar and / the admin Toolbar on a post compose (edit) window.

== Screenshots ==

1. The Reminder compose window
2. The diary edit page
3. The Simple Diary options
4. The Simple Diary widget in the admin page
5. The widget in the Twenty Fourteen theme sidebar (frontend)
6. The diary page in the Twenty Fourteen theme (frontend)
7. A single reminder in the Twenty Fourteen theme (frontend)


== Changelog ==

= 1.4.1 =
* Now you can enable or disable the reminder creation link (in sidebar metabox or in admin toolbar) in post compose window
* Avoid script loading in frontend (should only be loaded in backend)

= 1.4 =
* Fixed date and time input validation (looks if the interval between begin and end of event is right before validating)
* Improved new WordPress editor Gutenberg integration (now link to create a new reminder from a post is located in admin Toolbar and not in sidebar metabox)
* The Toolbar link change dynamically when publishing / unpublishing the post in Gutenberg
* Using minified version of `.css` and `.js` file


= 1.3 =
* Added two fields in reminder compose windows: link text and reminder type (so you can customize each reminder using a class).
* You can now add a reminder from a post (this adds the link to the post in the reminder automaticaly)
* New template functions added: `the_simdiaw_upcoming_reminders_query()`, `the_simdiaw_past_reminders_query()`, `the_simdiaw_past_reminders()`. See in the `/simple-diary/simdiaw-template-functions.php` file to see how they work.
* Restyling the admin reminder compose window to get it display well on all devices.
* Added options in option page: define _Past Reminders count_, _Custom reminders Names and Classes_, _Formating the upcoming and past reminders display_, _Time interval in the time selection window_. 
* Fixed some first use issues in option page.

= 1.2.1 =
* Fixed text domain issue (some strings weren't translated)

= 1.2 =
* Implemented latest pickadate package (3.5.6)
* Using get_locale() function to retrieve the locale instead of WP_LANG
* Allow to change the date and time display format in reminder compose window (two new options have been added for this)
* Provide a clean uninstall process (all plugin options and reminders are removed when uninstalling the plugin)

= 1.1 =
* Make edit page responsive
* Adding new options: reminder count for diary, column to display in edit page selection.
* Fixing typos in translation
* Internationalization of theme support template improved
Note: you should replace the previous theme support files you uploaded into the right theme folder by this new set.

= 1.0 =
* First release. Thanks for your feedback!
