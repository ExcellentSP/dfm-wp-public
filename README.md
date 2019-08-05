# dfm-wp-public

Technical Exercise

1. Set up a local WordPress development environment using VVV (https://github.com/Varying-Vagrant-Vagrants/VVV), MAMP, or any other local development setup you prefer.
2. Fork the DFM WP Public repo here: https://github.com/dfmedia/dfm-wp-public
3. Clone the fork into the plugins folder of your local WordPress environment
4. Add functionality to the plugin as outlined below. When you're finished send us a link to your github repo with the branch with your work.

Notes:

Create a new class for your functionality. Note the setup_dependencies function in class-dfm-wp-public.php for including your new class.

Document your code as best as you can.

Try to use WordPress PHP coding standards as best you can (https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/), but don't dwell on this point if you're not used to writing code with these standards.


This plugin should add 5 admin pages to the WordPress Dashboard and be accessible by clicking a menu item in the main Dashboard navigation. 

Any database queries should be optimized as much as possible.

When clicking the menu item in the Dashboard, I should see the following:

Page 1: 

Title: Sports
Content: An unordered list of the 25 most recent posts from the "Sports" category. If there are no posts in the "Sports" category, I should see a line of text that states: "There are no Sports posts. Check back later"

Page 2:

Title: Animals
Content: An unordered list of the 10 most recent posts from the "Animals" category. If there are no posts in the "Animals" category, I should see a line of text that states: "There are no Animals posts. Check back later"

Page 3:

Title: Business
Content: An unordered list of the 12 most recent posts from the "Business" category. If there are no posts in the "Business" category, I should see a line of text that states: "There are no Business posts. Check back later"

Page 4: 

Title: Entertainment
Content: An unordered list of the 50 most recent posts from the "Entertainment" category. If there are no posts in the "Entertainment" category, I should see a line of text that states: "There are no Entertainment posts. Check back later"

Page 5:  

Title: World and News
Content: An unordered list of the 100 most recent posts from the "World and News" category. If there are no posts in the "World and News" category, I should see a line of text that states: "There are no World and News posts. Check back later"

## Solution Notes
You can edit what categories are in the listing by modifying the `categories` array in  `/includes/class-dfm-wp-admin-category-listing.php`.

It was not specified if we were to create categories if they didn't exist, so I added a tiny bit of error handling to ensure the user creates the necessary categories and configures them properly.