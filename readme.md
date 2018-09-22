# JHL Auto Login

## Disclaimers/Warnings

**This can be super insecure and shouldn't be used unless you have a really good reason to**

**Please don't use the first section where you just pick a name to log in as unless you are locally developing something**

**This code isn't that great, I don't think I'm even using nonces... I might update this eventually, butt don't hold your breath**

## Instructions

Download the zip and install it via the WordPress admin, or unzip/clone to the plugins folder, in a folder named `jhl-auto-login`

Go to the plugins page and activate it

Go to the `Settings` menu, and then `JHL Auto Login`

Enable the plugin by selecting the `Yes` radio button

You can either choose a user to directly log in as (this is for anyone visiting the site, so **PLEASE** don't do that), or you can set a parameter to tie to a user meta key, and it will find that and log that user in

## Use Case

You generate an email to someone and want them to click on a link and have it automatically log them in, so they never have to deal with a username and password

Go to the `JHL Auto Login` settings

In the **User Meta Key** field, enter `invite_hash`

In the **Query String Parameter** field, enter `invite` or whatever you want to use as the key in the query string

Go to the `Users` menu

Edit a user

Scroll to the bottom

Where it says **Invite Hash**, either enter a value, or click on the `Generate Hash` button to make a random string.

That hash is what you will use to log the user in.

So let's say the hash is `foo-bar-baz`.

Assuming we used `invite` for the Query String Parameter, the url to log that user in would be `example.com?invite=foo-bar-baz`