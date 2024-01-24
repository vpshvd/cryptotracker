### service containers:

```bash
docker compose -f docker-compose.services.yml -p cryptotracker-services up -d
```

### php and caddy containers:

```bash
docker compose up -d --build
```

## Project Description: Bitcoin Exchange Rates API

### Overview

This web application provides an API to access the hourly exchange rates of Bitcoin (BTC) against various fiat
currencies like USD, EUR, etc. It's designed for building currency rate charts and allows users to specify the date
range for which they want the data.


### Key Features

- Fetch hourly exchange rate data for BTC against various currencies.
- Customize the output range for the exchange rate data.
- Automated updates of currency rates from a real exchange.
- Supports a minimum of three currency pairs.

### Setup
1. List the supported currency pairs in the .env file of the Symfony project. 
For example, 
> BASE_CURRENCY_CODE=BTC  
> QUOTE_CURRENCY_CODES=USD,AUD,CAD,GBP

Find list of all available quote currencies here: https://blockchain.info/ticker (BTC stays put)

2. Populate the database with currency pairs defined in the .env file.
```bash 
docker exec cryptotracker-php bin/console app:populate-currency-pairs 
```

3. Update the latest exchange rates whenever you want.
```bash 
docker exec cryptotracker-php bin/console app:update-exchange-rates 
```

4. Setup up Cron Job for Automatic Updates:  
```bash 
docker exec -it cryptotracker-php /bin/sh
```
- crontab -e  
- add this line:  
0 * * * * /usr/local/bin/php /srv/app/bin/console app:update-exchange-rates


### API Usage
Endpoint: GET /api/exchange-rates/{baseCurrency}/{quoteCurrency}

- Retrieves the exchange rates for the specified currency pair within a given date and time range.
- Parameters:
  - baseCurrency: The base currency code (e.g., BTC).
  - quoteCurrency: The quote currency code (e.g., USD).
  - start: The start date and time for the data range (optional, default is 24 hours before the current time).
  - end: The end date and time for the data range (optional, default is the current time).

Example Request:  
GET localhost:8950/api/exchange-rates/BTC/USD?start=2024-01-24T11:00:00&end=2024-01-24T13:00:00

```json
[
    {
        "rate": 39978.91,
        "timestamp": "2024-01-24 11:00:00"
    },
    {
        "rate": 40005.6,
        "timestamp": "2024-01-24 12:00:00"
    },
    {
        "rate": 40219.97,
        "timestamp": "2024-01-24 13:00:00"
    }
]
```

### Handling Invalid Requests:

If invalid currency codes are provided, the API will return an HTTP 400 Bad Request status with an appropriate error message.
If the start date is after the end date, the API will also return an HTTP 400 status.
