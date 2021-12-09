# Vdomah.Roles plugin adapter for RainLab.User plugin

This is not independent plugin. It's used to connect two plugins by requiring both. 
The reason I had to do it this way - because Vdomah.Roles supports both Lovata.Buddies and RainLab.User
and it can't use $require directive to require both because they are mutually exclusive.

So to use Vdomah.Roles with RainLab.User you need to install just this plugin. 
It's the only purpose of it.

## Installation

### Artisan

Using the Laravel’s CLI is the fastest way to get started. Just run the following commands in a project’s root directory:

```bash
php artisan plugin:install vdomah.rolesrainlabuser
```

Or go to your project's Backend Updates section or to plugin's marketplace page and install it.