#Mille Bornes

##URLs - this will change

- DOMAIN_NAME/index.php - Main homepage will be 
- DOMAIN_NAME/index.php/admin - Admin where you can update data in database by clicking Strava button, will take a while, as it's also requestion Google Directions, will add better feedback
- DOMAIN_NAME/index.php/map - test to show data on map

## CodeIgniter templates

There are 3 files of interest. 

1) application/views/templates/header.php -> header inserted to every page, add links to css files here
2) application/views/templates/footer.php -> footer inserted to every page, add links js scripts here
3) application/views/pages/index.php -> main page template

## Adding CSS a JS files

Adding to application/views/templates/header.php and application/views/templates/footer.php, instead relative address, each link should start with <?php echo base_url(); ?>, e.g. 
```html 
<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
```

## Data

Sample database dump is at temp folder. 
