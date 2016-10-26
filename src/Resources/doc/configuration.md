## Configuration

In ```security.yml``` include one or both providers to enable them

```yaml
# To get started with security, check out the documentation:
# http://symfony.com/doc/current/book/security.html
security:
    # http://symfony.com/doc/current/book/security.html#where-do-users-come-from-user-providers
    providers:
            login:
                id: damijanc_user_provider
            api_key:
                id: damijanc_api_key_user_provider
```

and then for login add this into firewall section:

```yaml
#user provider we are using
            simple_form:
                authenticator: damijanc_authenticator
```

or for api key enabled login:
                
```yaml
simple_preauth:
                authenticator: damijanc_api_key_authenticator
```

Full security.yml example might look something like:

```yaml
# To get started with security, check out the documentation:
# http://symfony.com/doc/current/book/security.html
security:
    # http://symfony.com/doc/current/book/security.html#encoding-the-user-s-password
    encoders:
        Symfony\Component\Security\Core\User\User: plaintext

    # http://symfony.com/doc/current/book/security.html#hierarchical-roles
    role_hierarchy:
        ROLE_USER:  ROLE_COMMON
        ROLE_ADMIN: ROLE_COMMON
        
    # http://symfony.com/doc/current/book/security.html#where-do-users-come-from-user-providers
    providers:
            login:
                id: damijanc_user_provider
            api_key:
                id: damijanc_api_key_user_provider

    firewalls:
        # disables authentication for assets and the profiler, adapt it according to your needs
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        default:
            pattern:    ^/

            anonymous:    true #enable anonymous security provider

            logout:
                path:   logout
                target: login

            simple_preauth:
                authenticator: damijanc_api_key_authenticator

            #user provider we are using
            simple_form:
                authenticator: damijanc_authenticator
                login_path: login
                check_path: login_check

    # with these settings you can restrict or allow access for different parts
    # of your application based on roles, ip, host or methods
    # http://symfony.com/doc/current/cookbook/security/access_control.html
    access_control:
        - { path: ^/, role: ROLE_ADMIN }
```