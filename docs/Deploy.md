# Deploy

[Return to Summary main page](README.md)

Tutorial to deploy your application to your local/staging/production server.

We use the [Easy Deploy Bundle][easy_deploy_bundle] to deploy the application.

## Requirements

The only requirement is that your local machine (where do you run the deploy command) has a valid ssh connection to connect to the server it will deploy to.

## Configuration Files

EasyDeploy uses plain PHP files to configure the deployment process. You can find the deploy configuration file for the prod environment in config/prod/deploy.php.

You can add a new environment deploy file using the [configuration tutorial of the Bundle][configuration].

## Usage

The project have two global commands called deploy and rollback. The deploy command publishes your application into one or more remote servers. The rollback command reverts the remote application to the previous version.

EasyDeploy can deploy to any number of servers, even when they are of different type (e.g. two web servers, one database server and one worker server). It also supports multiple stages, so you can tailor the deployed application to different needs (production servers, staging server, etc.)

Each stage uses its own configuration file. The default stage is called prod, but you can pass any stage name as the argument of the deploy/rollback commands:
    
    # deploy the current application to the "prod" server(s)
    bin/console deploy
    
    # deploy the current application to the "staging" server(s)
    bin/console deploy staging
    
    # rolls back the app in "prod" server(s) to its previous version
    bin/console rollback
    
    # rolls back the app in "qa" server(s) to its previous version
    bin/console rollback qa


[easy_deploy_bundle]:https://github.com/EasyCorp/easy-deploy-bundle
[configuration]:https://github.com/EasyCorp/easy-deploy-bundle/blob/master/doc/configuration.md#configuration
