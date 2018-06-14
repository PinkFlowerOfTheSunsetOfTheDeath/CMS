## PinkFlowerOftheSunsetOfTheDeath - CMS

You may follow User stories about features of the projects in [the wiki](https://github.com/PinkFlowerOfTheSunsetOfTheDeath/CMS/wiki).

You may also find all documentation in details in [the Wiki](https://github.com/PinkFlowerOfTheSunsetOfTheDeath/CMS/wiki)

### How to install 

In order to install properly, you will have to:

- Either Download source code from [Releases]() or use the Docker Image from DockerHub [amasselot/pinkFlower]()
- Install Dependencies using composer (`composer install`)
- Run your webserver inside `public` directory
- Login to your website's host

At first initialization of your website, you will be asked to follow multiple configuration steps in order to start using your application.
These steps include:
- Database configuration (host, port, username and password)
- First User registration (your `Admin` account to manage content)

During these steps, the Application Kernel will set up your database by installing the necessary data structures for your website to work properly. 

### Back Office

As the Administrator of the website, you will be able to have access to the back office to manage content.

In this panel, you will be able to:
- Manage Articles (posts) - `title`, `content`, `visibility`, `url slug`
- Manage Pages (usually static pages such as `about-us`)

### Customize

As a User of Myte Project, you will be able to create custom themes for your website's layout (front office) for end-users to visit.
More explainations in [this page of the wiki](https://github.com/PinkFlowerOfTheSunsetOfTheDeath/CMS/wiki/Create-a-custom-theme).