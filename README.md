# persistiveutm plugin for Craft CMS 3.x

Persist UTM values for tracking conversation rates

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require dispositiontools/craft-persistiveutm

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Persistive UTM.

## persistiveutm Overview



## Configuring persistiveutm



## Using persistiveutm

Add this hook to your layout to trigger saving utm details to cookies:
{% hook 'persistiveutm' %}

You can write your own code to save details to cookies if you wish.
The cookies are:
pUtmCampaign
pUtmMedium
pUtmSource
pUtmLanding
pUtmReferrer


## persistiveutm Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Disposition Tools](https://www.disposition.tools)
