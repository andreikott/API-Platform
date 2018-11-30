RentCRM
=======
> About soon...  


First start
-----------
1. Pull the repo
2. Create .env files in **root**, **client** and **admin** folders
3. Run `docker-compose up` or `docker-compose up -d` for running in background
4. You`re done! :thumbsup:  
Visit [localhost](http://localhost)

There are commands for convenience:
```bash
git clone git@95.46.45.196:Rent-CRM/rent-crm.git rent-crm
cd rent-crm
cp .env.dist .env && cp client/.env.dist client/.env && cp admin/.env.dist admin/.env && cp api/.env.dist api/.env
docker-compose up -d
```

Troubleshooting
---------------

#### Container starting error
**Problem**  
Bind address already in use.  

*For example:*
```
Starting rentcrmapiplatform_client_1 ... 
Recreating rentcrmapiplatform_db_1 ... 
Recreating rentcrmapiplatform_db_1 ... error

Starting rentcrmapiplatform_admin_1 ... done

ERROR: for db  Cannot start service db: driver failed programming external connectivity on endpoint rentcrmapiplatform_db_1 (58e2492080756f176027c722e6521183b2a65aca9d455621d4b5c6cdd937e870): Error starting userland proxy: listen tcp 0.0.0.0:5432: bind: address already in use
ERROR: Encountered errors while bringing up the project.

```
**Solution**  
Just change port of trouble container in **.env** file.  

*For this example is:*
```yaml
CONTAINER_REGISTRY_BASE=quay.io/api-platform
NGINX_PORT=8080
VARNISH_PORT=8081
POSTGRES_PORT=5433 <- changed port from 5432 to 5433
ADMIN_PORT=81
CLIENT_PORT=80
```

***

<namespace/project>  
namespace/project>
