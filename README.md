<p align="center">
    <a href="#" target="_blank">
        <img src="https://static.vecteezy.com/system/resources/previews/000/623/175/non_2x/vector-water-wave-symbol-and-icon-logos.jpg" height="100px">
    </a>
    <h1 align="center">EMS Web Application</h1>
    <br>
</p>



EMS is a web based application that alows an organzation to manage 
all their task electronical through the system and monitor all 
activities such as margins profits, daily targets, 
upto date reports etc. The entire project was done on an MVC platform

Documentation is at docs/guide/README.md.

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
# ems
