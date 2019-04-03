# LSX Team

The [LSX Team plugin](https://lsx.lsdev.biz/extensions/team/) adds a section to your website for storing your team members information. 

Create a profile per team member, display them in an attractive carousel and house them all in an archive for all your users to view. 

## Description
People like to see a face behind a business, it gives you a more personal, approachable relationship with your users. 

The [LSX Team plugin](https://lsx.lsdev.biz/extensions/team/) plugin allows you to display your team profiles beautifully on your website. 

## Works with the LSX Theme
Our  [theme](https://lsx.lsdev.biz/) works perfectly with the Team Extension, improving internal linking, website SEO and user experience! 

## Gutenberg Compatible
Have you updated to the new WordPress Gutenberg editor? We've got you covered! [The LSX Theme](https://lsx.lsdev.biz/) and all of its extensions have been optimised for the Gutenberg update. 

## It's free, and always will be.
We’re firm believers in open source - that’s why we’re releasing the [LSX Team plugin](https://lsx.lsdev.biz/extensions/team/) plugin for free, forever. 

## Support
We offer premium support for this plugin. Premium support that can be purchased via [lsdev.biz](https://www.lsdev.biz/services/support/.

## Installation
You can also download and install the extension directly from the backend of your website

1. Login to the backend of your website.
2. Navigate to the “Plugins” dashboard item.
3. Select the “Add New” option on the plugins page.
4. Search for “LSX Team” in the plugin search bar.
5. Download and activate the plugin.

## Frequently Asked Questions
### Where can I find LSX Team plugin documentation and user guides?
For help setting up and configuring the Team plugin please refer to our [user guide](https://www.lsdev.biz/documentation/lsx/team-extension/)
### Will the LSX Team plugin work with my theme?
No; Not unless you are making use of the [The LSX theme!](https://lsx.lsdev.biz/) 
All of the LSX Extensions were built for the LSX theme. Be sure to have it installed and activated for this extension to function. 
### Where can I report bugs or contribute to the project?
Bugs can be reported either in our support forum or preferably on the [LSX Team GitHub repository](https://github.com/lightspeeddevelopment/lsx/issues).
### The LSX Team plugin is awesome! Can I contribute?
Yes, you can! Join in on our [GitHub repository](https://github.com/lightspeeddevelopment/lsx-team) :)

## Changelog

### 1.1.2
* Dev -  Fixed Package-Json.

### 1.1.1
* Dev - Wordpress.org sanatizing recommendations.

### 1.0.0
* Dev - Initial release.

## Upgrade Notice

## 1.1.2
Fixed the Package-Json error. 

## Shortcode:

Click on the Team Members button in the editor to bring up the shortcode UI. Select your options and click on the 'Insert Team Members' button to insert the shortcode into the body of your page / post.

### Parameters:

- Layout
 - description: choose either the 'standard' unstyled layout or the 'panel' layout which displays each team member in a Bootstrap panel - http://getbootstrap.com/components/#panels
 - parameter name: layout
 - accepts: standard / panel
- Columns
 - description: Number of columns per row of team members
 - parameter name: columns
 - accepts: 1 / 2 / 3 / 4
- Order By
 - description: Sort retrieved posts by parameter 
 - parameter name: orderby
 - accepts: none / id / name / date / modified / rand / menu_order
- Order
 - description: Designates the ascending or descending order of the 'orderby' parameter
 - parameter name: order
 - accepts: ASC / DESC
- Maximum Amount
 - description: Maximum amount of team members to display
 - parameter name: limit
 - accepts: numeric value (leave empty to display all)
- Role
 - description: Filter team members by a role
 - parameter name: role
 - accepts: numeric value (leave empty to display all)
- Specify Team Members by ID
 - description: Include specific team member post IDs to display specific team members.
 - parameter name: include
 - accepts: comma seperated list of team member post IDs
- Image Size
 - description: Set the size of team member images
 - parameter: size
 - accepts: numeric value (applied to both width and height)
- Show Image Rounded
 - description: Choose whether or not to display each rounded Team Member's image
 - parameter: show_image_rounded
 - accepts: 0 (no) / 1 (yes)
- Link Titles
 - description: Whether or not to link titles to single team member posts. (If 'Disable Single' is checked in the plugin settings, this parameter will be ignored and titles won't be linked)
 - parameter: link
 - accepts: 0 / 1
- Show Roles
 - description: Choose whether or not to display each Team Member's assigned role
 - parameter: show_roles
 - accepts: 0 (no) / 1 (yes)
- Show Descriptions
 - description: Choose whether or not to display each Team Member's description
 - parameter: show_desc
 - accepts: 0 (no) / 1 (yes)
- Show Social Icons
 - description: Choose whether or not to display each Team Member's social icons
 - parameter: show_social
 - accepts: 0 (no) / 1 (yes)

## Template Tag:

The team function can be called directly in your theme templates. It accepts an array of the same parameters used in the shortcode.

eg:
```
<?php
	if ( class_exists( 'LSX_Team' ) ) {
        lsx_team( array(
            'size' => 150,
            'show_social' => false,
            'columns' => 3,
            'limit' => 6
        ) );
    };
?>
```

## Widget:

Insert the Team Members widget into a sidebar on the widget admin page and configure the settings.

## Plugin Options:

- Disable Single Posts: Check this to prevent single Team Members from displaying
- Placeholder Image: Upload a placeholder image to use if a Team Member does not have an image available
