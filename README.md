# To-Do application using VueJS & Laravel

Basic to-do list application made to get more familiar with both Vue and Laravel. It has a Laravel API that is consumed by the Vue front-end. The authentication was done using Laravel's passport.

### Some improvements to be done:

* Kick out users with expired/invalid access tokens;
* Vuex for a centralized store and better state management between all the components;
* Login & Register client validations;
* Other functionalities like pagination, associate labels/projects to tasks, etc.;

### Important:

Because of some api calls "hanging" using the regular http://localhost:8000 (**php artisan serve**), i set up a virtualhost using xampp (http://todonuno.test) which is what i set in the env file as the baseurl for every request. 
Here's how to do that (using xampp):

1. Open the “httpd-vhosts.conf” file that is located in `C:\xampp\apache\conf\extra` and add the lines of code below.

```
<VirtualHost todonuno.test:80>
  DocumentRoot "C:\xampp\htdocs\todo_nuno\public"
  ServerAdmin todonuno.test:80
  </Directory>
</VirtualHost>
```

2. Open  `C:\Windows\System32\drivers\etc\hosts` and add the below line at the end of the file. 

`127.0.0.1 todonuno.test`

**Note:** You can choose whatever url / document root you'd like. Just know that you'll have to change the env file accordingly.

### How to get it working:

1. Navigate to the project folder;
2. Make sure that you have all the dependencies by running `composer install` and `npm install`;
3. Create the database and make sure it matches the one defined in the env file (check the credentials while you're at it);
4. Run the migrations: `php artisan migrate`;
5. Run `php artisan passport:install` to install the passport. You don't need to do anything with the generated secrets because the one being used by default by the app will already be in the database at this point.
5. That's it.
