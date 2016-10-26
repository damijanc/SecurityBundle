![Travis status]
(https://api.travis-ci.org/damijanc/SecurityBundle.svg?branch=master)
# SecurityBundle

Are you using Symfony and you do not have a db access for authentication?
Are you using API and want to use it for authentication. Then this bundle is for you.

With this bundle you get two authentication providers (login and api key) 
that you can use to login.

Bundle will raise event that you need to implement listener for and do authentication there.
This way you are decoupled from db access that is implied when using Symfony security. 

#Instalation
```
composer require damijanc/security-bundle
```

#Contribution


#Todo
- Update documentation 
- Make a sample project
- ...
