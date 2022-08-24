# OpenSIPS Provisioner V0.0.1

#WARNING: Not production ready, yet!

This is a simple PHP based web application to assist in programatic provisioning of the OpenSIPS platform. The OpenSIPS Control Panel is great but lacks simple and robust API's meaning you can't intergrate it into your automated provisioning. This let's you. It also has a lightweight Web UI that alls you to make changes to aspects of the software.

## Dependencies

The following are dependencies that will need to be installed using composer prior to running the application. An explination of each is below.

vlucas/phpdotenv: This is used to load the environment variables
altorouter/altorouter: This is the routing function that allows for URI's
catfan/medoo: This is the database adapter
league/plates: This is the template engine for the Web UI


## Authentication

The portal is designed for use on your own internal systems not on the open internet so does not currently have any sort of authentication. It does encoperate API Keys to stop requests from making changes to OpenSIPS across your network without permission.


## Provisions



## Database Tables

OpenSIPS Provisioner requires a couple of tables to be created in order for the software to function. You can choose where to put these tables they can either be in their own database or you can store them inside the OpenSIPS database due to the use of the 'ops_' prefix meaning they won't conflict with any of OpenSIPS tables.

osp_servers - This holds the servers you want to be provisioned through OpenSIPS Provisioner, we separated this so you can have gateways that are not provisioned by this software.

osp_provisions - This table holds all the provisions you setup these run right before a row is inserted into OpenSIPS. This could be an API call to create a Subscriber on a destination server before inserting it into OpenSIPS.


### Initial Release



### 11.08.2022

osp_audit + Addition of field 'user' to collect User ID

### 19.08.2022

osp_provision + Addition of opperation field to record when action should be carried out 'create', 'update', 'delete'