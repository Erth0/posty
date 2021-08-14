
![Posty](https://raw.githubusercontent.com/Erth0/posty/main/art/cover.png)
## Table of Contents
- [Table of Contents](#table-of-contents)
- [Introduction](#introduction)
- [Installation](#installation)
- [Available Commands](#available-commands)
- [Roadmap](#roadmap)
- [Running Tests](#running-tests)
- [License](#license)
- [Credits](#credits)
- [Badges](#badges)
## Introduction
Posty is a simple way to manage your articles with a simple to use command line tool.
## Installation

`composer global require erth0/posty`

From the project where you would like to add the blog posts you need to require the `posty-laravel-client`:

`composer require erth0/posty-laravel-client`

After finishing with the installation of `posty-laravel-client` you need to generate the api key which will be used by the cli.

`php artisan posty:generate`

This will generate the api key which you need to copy and save it for later use and you need to add the `POSTY_HASHED_API_KEY` env in your project environment.
This will be used to connect the posty cli with your project.

Next you need to create a folder in your local machine and `cd` in the folder then you need to run the `posty link` **command** so you can link the website you would like to manage the articles.
You will be promted to enter few details such as:

- Project Name (required)
- API Endpoint (required)
- API Endpoint Prefix (optional)
- API Key (required)

After all the configurations have been set you will get a success message and this folder is linked with the above project.
To make sure the folder was linked successfully with the project you can test it with `posty test` **command**
## Available Commands
- `posty link` (This will link the folder you are in with the desired project)
- `posty unlink` (This will unlink the folder you are in with the linked project)
- `posty update` (This will update project configurations)
- `posty projects` (This will list all the linked folders with projects)
- `posty test` (This will test the connection between cli and the client)
- `posty topics:list` (This will list all the topics)
- `posty topic:create` (This will create a new topic)
- `posty topic:update` (This will update topic)
- `posty topic:delete` (This will delete topic)
- `posty tags:list` (This will list all the tags)
- `posty tag:create` (This will create a new tag)
- `posty tag:update` (This will update tag)
- `posty tag:delete` (This will delete tag)
- `posty create` (This will create a new draft article)
- `posty update my-first-article.md` (This will update the article)
- `posty delete my-first-article.md` (This will delete the article)
- `posty sync` (This will synchronize all the articles within the linked project)

## Roadmap

- Additional browser support

- Add more integrations

## Running Tests

To run tests, run the following command

```bash
    ./vendor/bin/pest
```
## License

[MIT](https://choosealicense.com/licenses/mit/)


## Credits

- [@emukja](https://www.github.com/erth0) for development.


## Badges

Add badges from somewhere like: [shields.io](https://shields.io/)

[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://github.com/tterb/atomic-design-ui/blob/master/LICENSEs)
