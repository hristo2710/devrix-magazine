# DevriX Magazine

A custom responsive WordPress magazine theme developed for the DevriX Front End Challenge.

The project uses a custom WordPress theme with dynamic content managed through the WordPress administration panel.

## Features

- Custom WordPress theme built with PHP, HTML, CSS and JavaScript
- Custom Article post type and article categories
- Featured and Breaking News sections
- Interactive Featured News section that updates when hovering over the latest articles
- Live article search using the WordPress REST API
- Responsive layout and mobile navigation
- Individual article pages
- Editable articles, categories and featured images through the WordPress admin panel
- Automatic demo image importer
- Automatic demo navigation menu setup

## Technologies

- WordPress
- PHP
- HTML5
- CSS3
- JavaScript
- WordPress REST API
- Gutenberg Block Editor

## Installation

### 1. Install WordPress

Install WordPress in your preferred local development environment, such as Local.

Create a new WordPress site and make sure it is running.

### 2. Install the Theme

Clone or download this repository.

Copy the `devrix-magazine` folder into your WordPress installation:

`wp-content/themes/`

Open the WordPress administration panel and navigate to:

**Appearance → Themes**

Activate **DevriX Magazine**.

### 3. Import Demo Content

The `demo-content` directory contains the demo images and the WordPress XML export.

To import the articles:

1. Navigate to **Tools → Import**.
2. Find **WordPress** and install the WordPress Importer if necessary.
3. Click **Run Importer**.
4. Upload the XML file from the `demo-content` directory.
5. Assign the imported content to your existing WordPress administrator.
6. Complete the import.

The imported articles and categories should now be available in the WordPress administration panel.

### 4. Import Demo Images

Because the original project was developed locally, WordPress may not be able to download the images automatically during the XML import.

The theme includes a custom tool to restore the demo images and assign them to their corresponding articles.

After importing the XML file:

1. Navigate to **Tools → Import Demo Images**.
2. Click **Import Demo Images**.
3. Wait for the import to finish.

The tool imports the supplied images into the WordPress Media Library and assigns the corresponding featured images to the imported articles.

Articles that did not originally have featured images are left unchanged.

### 5. Configure the Navigation Menu

The theme includes an automatic menu setup tool.

To configure the main navigation:

1. Navigate to **Tools → Setup Demo Menu**.
2. Click **Setup Demo Menu**.

The tool creates or updates the Main Menu, removes duplicate menu items and assigns the menu to the theme's primary navigation location.

The menu includes links to the following homepage sections:

- News
- Sex
- Technology
- Sport
- Healthcare

**Note:** Running the menu setup tool again replaces the existing items in Main Menu.

### 6. Configure Permalinks

Navigate to **Settings → Permalinks**.

Select **Post name** and click **Save Changes**.

This refreshes the WordPress permalink configuration.

## Demo Content

The `demo-content` directory contains the resources needed to populate the demonstration website.

The WordPress XML export contains the demo articles, categories and associated content.

The `images` directory contains the demonstration images.

The custom import tools simplify the process of restoring the original website on a new WordPress installation.

## Project Structure

```text
devrix-magazine/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── demo-content/
│   ├── images/
│   └── WordPress XML export
├── inc/
│   ├── devrix-demo-image-import.php
│   └── devrix-demo-menu-setup.php
├── front-page.php
├── functions.php
├── header.php
├── footer.php
├── index.php
├── single-article.php
├── style.css
└── README.md
```

Additional theme files may be present in the repository.

## Content Management

Articles can be created and edited directly through the WordPress administration panel using the Gutenberg Block Editor.

Each article can have a title, content, category and featured image.

The theme also provides settings for marking articles as Featured or Breaking News.

The homepage retrieves articles dynamically using WordPress queries.

## Interactive Features

**Featured News**

Hovering over an article in the Latest News section updates the main Featured article's image, title, description and links.

**Live Search**

The search interface retrieves matching articles through a custom WordPress REST API endpoint.

**Responsive Navigation**

The navigation adapts to smaller screens and includes a mobile menu.

## Notes

- WordPress and its database are not included in this repository.
- The supplied XML file and custom import tools are intended for setting up the demonstration content.
- The demo image and menu setup tools are accessible through the WordPress administration panel.
- The article archive page is not part of the completed demo functionality.