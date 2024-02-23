# Dockerized symfony API

## Prepare Environment

### Prepare Local Environment

   ``` bash
   sh prepare-local-env.sh
   ```

## Access Application

### Local Environment (All port are configured in docker-compose file)
1) Access API
    ```
    http://localhost:8741/
    ```

2) Access phpMyAdmin
    ```
    http://localhost:8756/
    user    : root
    passwor : wg2bAQhd36aJ
    ```   

## Access Endpoints
### Get List of Notebook 
`GET http://localhost:8741/notebook`

### Get Notebook by id
`GET http://localhost:8741/notebook/<ID>`

### URL parameters
| Field   | Type    | Description  |
|---------|---------|--------------|
| id      | integer | Notebook ID  |

### DELETE Notebook by id
`DELETE http://localhost:8741/notebook/<ID>`

### URL parameters
| Field   | Type    | Description  |
|---------|---------|--------------|
| id      | integer | Notebook ID  |

### CREATE Notebook
`CREATE http://localhost:8741/notebook`

### Body parameters
| Field      | Type   | Description       |
|------------|--------|-------------------|
| identifier | string | unique identifier |
| headline   | string | headline          |
| content    | string | description       |

### PATCH Notebook
`PATCH http://localhost:8741/notebook/<ID>`

### URL parameters
| Field   | Type    | Description  |
|---------|---------|--------------|
| id      | integer | Notebook ID  |

### Body parameters
| Field      | Type   | Description       |
|------------|--------|-------------------|
| identifier | string | unique identifier |
| headline   | string | headline          |
| content    | string | description       |

## Run Tests

   ``` bash
   docker-compose exec app vendor/bin/phpunit tests/
   ```
