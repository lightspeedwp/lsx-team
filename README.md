# LSX Team

The LSX Team Extension provides a custom post type that allows you to easily show off the people that make up your business.

## Setup

### 1: Install NPM
https://nodejs.org/en/

### 2: Install Gulp
`npm install`

This will run the package.json file and download the list of modules to a "node_modules" folder in the plugin.

### 3: Gulp Commands
`gulp watch`
`gulp compile-css`
`gulp compile-js`
`gulp wordpress-lang`

## Post Type and Fields:

On activation, the LSX Team plugin creates a Team Members post type on your site. 

### Team Members Post Type fields and taxonomy

- Post Title: Team member's name
- Post Body: Team member's description
- Roles: Categorise team members into different roles with the hierarchical Roles taxonomy
- Featured Image: Team member's photograph or image
- Job Title: Team member's job title
- Location: Team member's location
- Contact Email Address: Team member's email address.
- Gravatar Email Address: If a featured image has not been set, this field will be used to display the team member's Gravatar if one is available.
- Telephone Number: Team member's phone number
- Mobile Number: Team member's mobile number
- Fax Number: Team member's fax number
- Skype Name: Team member's skype name
- Facebook URL: Full link to team member's Facebook profile
- Twitter URL: Full link to team member's Twitter profile
- Google Plus URL: Full link to team member's Google Plus profile
- LinkedIn URL: Full link to team member's LinkedIn profile
- Site User: If the team member is registered as a user of the site, you can link the team member to the site user.

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
