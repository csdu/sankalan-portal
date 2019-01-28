# Sankalan Portal
Sankalan is the annual tech fest organised by students of Department of Computer Science, University of Delhi. This is student managed portal, built with [Laravel](https://laravel.com), [Vue.js](https://vuejs.org) & [TailwindCSS](https://tailwindcs.com).

## Local Development
This is project is built with Laravel & Vue.js. Before you can start local development you need to take care of a few prerequisites, required by laravel. 

You can also setup a Local Development Environment using [Laravel Homestead](https://laravel.com/docs/5.7/homestead). Here are some one-time steps to get you started:

- [Installing Prerequisites](#installing-prerequisites)
- [Clone Project](#clone-project)
- [Install project dependencies](#install-project-dependencies)
- [Configure Application](#application-configuration)

### Installing Prerequisites
You can find the server prequisites listed in [laravel docs](https://laravel.com/docs/5.7/installation#server-requirements), Additionally, you would require to install (composer)[https://getcomposer.org/] & (nodejs)[https://nodejs.org/en/] to pull in all the project dependencies. For Linux, you can follow the instructions below to get all the prerequisites under the hood.

> Please note that these instructions are not tested yet, and *may not* work in first place.

##### Using `apt` package manager (Debian/Ubuntu)
Before you begin installing make sure you run `sudo apt update` to get the latest version available.

```bash

# if you do not have mysql installed on your system
sudo apt install mysql-server

# php & required extensions
sudo apt install php7.2 php7.2-mysql php7.2-xml php7.2-mbstring php7.2-bcmath php7.2-sqlite php7.2-json

# composer & nodejs
sudo apt install nodejs composer
```

##### Using `pacman` package manager (ArchLinux, Manjaro, Antergos)
Before you begin installing make sure you run `sudo pacman -Sy` to get the latest version available.

```bash
# if you do not have mysql installed on your system
sudo pacman -S mysql

# php & required extensions
sudo pacman -S php php-mysql php-xml php-mbstring php-bcmath php-sqlite php-json

# composer & nodejs
sudo pacman -S nodejs composer
```

##### Make sure you have pdo extension enabled in `php.ini`

Locate your php ini file using `php --ini`. For Ubuntu, it would be something like `/etc/php/7.2/cli/php.ini` and for Archlinux it is something like `/etc/php/php.ini`.

Search for the text `pdo-mysql` in this file if this line is commented i.e it is preceded with `;`, make sure you uncomment it by removing `;`.


### Clone Project
You can simply clone the project using `git` as:

```bash
git clone https://github.com/csdu/sankalan-portal
```

or you can simply download ZIP and extract it.

### Install project dependencies

Change into poject directory and install php dependencies using `composer`:

```bash
composer install
```

This will install all the php packages required by the project. Similarly, we would use `npm` to install all the required JavaScript dependencies.

```bash
npm install
```

This will install all the php packages required by the project.

### Application Configuration

Create a duplicate file of `.env.example` as `.env`.

```bash
cp .env.example .env
```

Generate an application key using `php artisan key:generate` this will add an application to your `.env` file. When you open `.env` file focus on the following part.

```env
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
...
```
Make sure you change database configuration according to your credentials. Mostly, you'd need to change values for these variables:

- `DB_DATABASE` - This is the name of database, you must change this and make sure the database name you provide exists, or would get an error.
- `DB_USERNAME` - This is your mysql user.
- `DB_PASSWORD` - This is your mysql password for that user.

That's pretty much it. You're done with the configuration.

### Start Local Development Server

To begin browsing & testing the portal you'd need to start a local development server.

```bash
php artisan serve
```

This serve your website at `localhost:8000`, you can now open this up in your browser.

But, wait a minute! we have not created the tables in our database, you might get error on some pages even. To create all the tables & seed your database with dummy data, run:

```
php artisan migrate --seed
```

---

## Contribution
All type of contributions are invited, if you find any bug, design issues, you can [create an issue](https://github.com/csdu/sankalan-portal/issues/new) or even open a Pull request to suggest some fixes. Thank you! 

