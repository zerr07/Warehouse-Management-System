# Warehouse Management System API documentation
#### Installation
You should manually edit configs/config.json by your needs.

After the config file is ready, fire `composer install` in terminal.


#### Error handling
It is recommended that you keep track of what response code you receive. A successful request always returns
with an HTTP code 200.

API can respond with an error even though it returned an HTTP code 200. In that case you should search for an
"error" key in the response.

Example response:
<pre>
{
    "error": "Unknown error",
    "code": "100"
}

This error means that an error is not recognised by the server. If you happen to receive this error contact the
developer so that this error could be handled in future. 
</pre>
| Key | Value type | Comment |
| :--- | :--- | :--- |
| error | String | Error message |
| code | Integer | Code of an error |

Every other type of errors will be described in the corresponding section below.

#### Authentication

In order to send any request to the API you need firstly obtain access token. To generate access token you need
to login into your WMS site and click on the "Generate new access token" button which is located in a sidebar.

Once you got access token you need to supply it to every request to the API. There are two ways the system can
read the token: 
<ul>
    <li>You can set a header variable with key "Token" and the token itself as a value
<pre>
Example JS Fetch:
var myHeaders = new Headers();
myHeaders.append("Token", "add_your_token_here");
var requestOptions = {
  method: 'GET',
  headers: myHeaders
};
fetch("http://dev.azdev.eu/api/reservations", requestOptions);
    </pre>
    </li>
    <li>You can supply it as query parameter in the URL
<pre>
Example JS Fetch:
fetch("http://dev.azdev.eu/api/reservations?token=add_your_token_here");
</pre>
    </li>
</ul>

**Errors**

| Code | Message |
| :---: | :---- | 
| 101 | Access token not supplied. |
| 102 | Either no user or multiple users with supplied access token. |
| 103 | SQL error, please contact administrator or check your request parameters. |
| 104 | You should use HTTPS in your requests. |

These errors can occur on every request since server check token on each request.

#### Reservations

| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | Yes |
| PUT | Yes |
| DELETE | Yes |

<b>GET</b> - Returns a list of reservations and its comments
Path : {Your_Domain}/api/reservations

Returns:

<pre>
{
"5837": {
        "comment": "Example reservation 3"
    },
    "5836": {
        "comment": "Example reservation 2"
    },
    "5835": {
        "comment": "Example reservation 1"
    },
    ...
}
</pre>
If you want to add shipments to the list you need to supply a key "display" with value "both" into your query.
Example:
<pre>
Path: {Your_Domain}/api/reservations?display=both

{
    "5836": {
        "comment": "Example reservation 2",
        "id_type": "1"
    },
    "5835": {
        "comment": "Example reservation 1",
        "id_type": "1"
    },
    "5834": {
        "comment": "Example shipment 1",
        "id_type": "2"
    },
    ...
}
</pre>

You can also supply "id" which value represents reservation ID. The request will return reservation data.

Example response: 
<pre>
{
    "id": "5844",
    "comment": "Example reservation",
    "date": "2021-01-12 12:42:58",
    "id_type": "1",
    "type_name": "Reservation",
    "products": [
        {
            "id": "7736",
            "id_reserved": "5844",
            "id_product": "50",
            "quantity": "2",
            "price": "5.98",
            "basePrice": "2.99",
            "id_location": "0",
            "tag": "AZ020",
            "name": {
                "et": "Example et product name",
                "ru": "Example ru product name"
            },
            "location": 0
        }
    ]
}
</pre>

**POST** - Creates a reservation

Path: {Your_Domain}/api/reservations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| comment | String | A reservation comment, value can be left empty | Yes |
| products | Array | An array of products where each key represents a product tag | Yes |
| quantity | Integer | Product quantity | Yes |
| price | Float/Double with 2 decimal places | Overall product price (price per piece*quantity) | No |
| basePrice | Float/Double with 2 decimal places | Price per piece | No |
<p>
  If price is not supplied it will be either calculated from supplied basePrice or from first platfrom sell price.<br>
  If basePrice is not supplied it will be either calculated from supplied price or retrieve from DB (first platfrom sell price)
</p>

Example request body:
<pre>
{
  "note": "Example reservation",
  "products":{
    "AZ020": {
      "quantity": 2,
      "price": 5.98,
      "basePrice": 2.99
    },
    "AZ041": {
      "quantity": 1,
      "price": 5.98
    }
  }
}
</pre>

**PUT** - Edit reservation

Path: {Your_Domain}/api/reservations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A reservation id | Yes |
| comment | String | A reservation comment | No |
| products* | Array | An array of products where each element represents product. Key may be the Id of product instance from reservation (Not product id itself) or product tag | No |
| quantity | Integer | Product quantity | Yes |
| price | Float/Double with 2 decimal places | Overall product price (price per piece*quantity) | No |
| basePrice | Float/Double with 2 decimal places | Price per piece | No |

\* - If you use "tag" as a key then you will be able to add and update product in 
reservation, if you use "id" then you will only be able to update.

Quantity key is mandatory if products key is specified.

Example request body:
<pre>
{
    "id": 5828,
    "comment": "Updated comment",
    "products": {
        "AZ041": {"quantity": 12},
        "AZ020": {"quantity": 1},
        "7711": {"quantity": 3, "basePrice": 1}
    } 
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 200 | No quantity field supplied. |
| 201 | Price or Base price is not number. |
| 202 | Unable to change comment, SQL error. |
| 203 | No products found in the array. |

**DELETE** - Cancels reservation

Path: {Your_Domain}/api/reservations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A reservation id | Yes |
| products | Array | An array of products where each key represents a product tag or its id from reservation (Not product id itself) | No |

Example request body:
<pre>
This will cancel reservation with id 5844.
{
    "id": "5844"
}

This will cancel only product with its reservation id of 7711 from reservation with id 5828. The reservation itself
will not be deleted unless the product deleted was not the last one in it.
{
    "id": "5828",
    "products": ["7711"]
}

This will cancel only product with tag AZ041 from reservation with id 5828. The reservation itself
will not be deleted unless the product deleted was not the last one in it.
{
    "id": "5828",
    "products": ["AZ041"]
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 300 | No reservation id supplied. |
| 301 | Could not get product in reservation by its tag for `$value`. |
| 302 | There is on or multiple products with this tag for `$value`. Please contact administrator. |

#### Confirm reservation

| Method | Allowed |
| :----: | :-----: | 
| GET | No |
| POST | Yes |
| PUT | No |
| DELETE | No |

**POST** - Confirms a reservation

Path: {Your_Domain}/api/reservations/confirm

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| card | Integer (1 or 0) | If 1 - 100% of payment will be registered as card payment otherwise cash | Yes |
| mode | String | Supported values "Bigshop", "Minuvalik", "Shoppa", "Osta" | Yes |
| client | String | Will only be applied if mode value is equal to "Bigshop", if empty "Eraisik" will be inserted | No |
| shipmentNr | String | External shipment number | No |
| id | Integer | Reservation id | Yes |
| products | Array | Array where each element is a product tag | No |
<p>
    Specifying "products" is only required when you want to confirm only certain products from the
    reservation. 
</p>

Example request body:
<pre>
{
  "card": 1,
  "mode": "Bigshop",
  "client": "Clients name",
  "shipmentNr": "123",
  "id": 5842,
  "products": ["AZ020", "AZ041"]
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 700 | Reservation id not supplied. |
| 701 | Error processing reservation with id: `id`. Check your request. |

#### Merge reservations

| Method | Allowed |
| :----: | :-----: | 
| GET | No |
| POST | No |
| PUT | Yes |
| DELETE | No |

**PUT** - Merges reservations

Path: {Your_Domain}/api/reservations/merge


<p>
    In request body you need to specify the array of reservation ids. The example below will merge two reservations with
    id 5555 and 4444.
</p>

Example request body:
<pre>
["5555", "4444"]
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 1200 | Failed to process query |
| 1201 | Reservation with id `id` is invalid type and cannot be merged |
| 1202 | Reservation with id `id` not found |
| 1203 | Invalid result received, please contact administrator. |


#### Sales

| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | Yes |
| PUT | No |
| DELETE | Yes |

**GET** - Displays list of performed sales with all relevant data

Path : {Your_Domain}/api/sale

Returns:

<pre>
{
    "8152": {
        "id": "8152",
        "cartSum": "0",
        "card": "0.00",
        "cash": "0.00",
        "arveNr": "14476986072",
        "ostja": "Eraisik",
        "sum": "0.00"
    },
    "8151": {
        "id": "8151",
        "cartSum": "0",
        "card": "0.00",
        "cash": "0.00",
        "arveNr": "14476905900",
        "ostja": "Eraisik",
        "sum": "0.00"
    },
</pre>

You can supply "id" key which value represents sale ID or "invoice" key which value represents "arveNr" keys value.
The request will return full sale data.

Example response: 
<pre>
{
    "id": "8146",
    "cartSum": "19.99",
    "card": "19.99",
    "cash": "0.00",
    "arveNr": "14467595841",
    "saleDate": "2020-12-09 13:44:09",
    "ostja": "Osta",
    "modeSet": "Osta",
    "tellimuseNr": "147688405",
    "shipment_id": null,
    "sum": "19.99",
    "tagastusFull": "",
    "products": {
        "11596": {
            "id": "11596",
            "id_sale": "8146",
            "id_item": "1666",
            "price": "19.99",
            "quantity": "1",
            "basePrice": "19.99",
            "id_location": "8443",
            "status": "Müük"
        }
    }
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 400 | No sale with this id. |

**POST** - Perform sale

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| card | Integer (1 or 0) | If 1 - 100% of payment will be registered as card payment otherwise cash | Yes |
| mode | String | Supported values "Bigshop", "Minuvalik", "Shoppa", "Osta" | Yes |
| client | String | Will only be applied if mode value is equal to "Bigshop", if empty "Eraisik" will be inserted | No |
| shipmentNr | String | External shipment number | No |
| quantity | Integer | Product quantity | Yes |
| price | Float/Double with 2 decimal places | Overall product price (price per piece*quantity) | No |
| basePrice | Float/Double with 2 decimal places | Product Price per piece | No |
<p>
  If price is not supplied it will be either calculated from supplied basePrice or from first platfrom sell price.<br>
  If basePrice is not supplied it will be either calculated from supplied price or retrieve from DB (first platfrom sell price)
</p>

Example request body:
<pre>
{
  "card": 1,
  "mode": "Bigshop",
  "client": "Clients name",
  "shipmentNr": "123",
  "products":{
    "AZ020": {
      "quantity": 2,
      "price": 5.98,
      "basePrice": 2.99
    },
    "AZ041": {
      "quantity": 1,
      "price": 5.98
    }
  }
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 600 | No mode key found. |
| 601 | No card key found. |


**DELETE** - Cancels sale

Path: {Your_Domain}/api/sale

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A sale id | Yes |
| products | Array | An array of products where each key represents a product tag or its id from sale (Not product id itself) | No |

Example request body:
<pre>
This will cancel sale with id 8155.
{
    "id": "8155"
}

This will cancel only product with its sale id of 11607 from reservation with id 8155. The reservation itself
will not be canceled unless the product canceled was not the last one in it.
{
    "id": "8155",
    "products": ["11607"]
}

This will cancel only product with tag AZ041 from sale with id 8155. The sale itself
will not be canceled unless the product canceled was not the last one in it.
{
    "id": "8155",
    "products": ["AZ041"]
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 500 | There is on or multiple products with this tag for `Value`. Please contact administrator. |
| 501 | Could not get product in reservation by its tag for `Value`. |
| 502 | No Sale id supplied. |


#### Shipments
**Shipment types**

| ID | Value | Allowed in the API |
| :----: | :----- | :----- | 
| 1 | Smartpost | Yes |
| 2 | Venipak | Yes(Partly) |
| 3 | Others | No |
| 4 | Pickup | Yes |

**Shipment status types**

| ID | Value |
| :----: | :----- | 
| 1 | No data |
| 2 | Carrier selected |
| 3 | Barcode generated |
| 4 | Label generated |
| 5 | Posted |
| 6 | Checked out |
| 7 | Data saved |
| 8 | Pickup from store |
| 9 | Ready for pickup |

**Smartpost types**

| ID | Value |
| :----: | :----- | 
| 1 | Default |
| 2 | Cash on delivery |
| 3 | Client pays the delivery |



| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | Yes |
| PUT | No |
| DELETE | Yes |

<b>GET</b> - Returns a list of shipments and its comments
Path : {Your_Domain}/api/shipments

Returns:

<pre>
{
    "5823": {
        "comment": "Example shipment 3"
    },
    "5822": {
        "comment": "Example shipment 2"
    },
    "5821": {
        "comment": "Example shipment 1"
    },
    ...
}
</pre>
If you want to add reservations to the list you need to supply a key "display" with value "both" into your query.
Example request body:
<pre>
Path: {Your_Domain}/api/shipments?display=both

{
    "5836": {
        "comment": "Example reservation 2",
        "id_type": "1"
    },
    "5835": {
        "comment": "Example reservation 1",
        "id_type": "1"
    },
    "5834": {
        "comment": "Example shipment 1",
        "id_type": "2"
    },
    ...
}
</pre>
<p>
A "display" key can also be equal to "checked" which would return all shipments with status 6.

You can also supply "id" which value represents shipment ID. The request will return shipment data.
</p>
Example response: 
<pre>
{
    "id": "5069",
    "comment": "M #99999-99999",
    "date": "2020-11-26 12:24:22",
    "id_type": "2",
    "type_name": "Shipment",
    "products": [
        {
            "id": "6732",
            "id_reserved": "5069",
            "id_product": "958",
            "quantity": "6",
            "price": "8.34",
            "basePrice": "1.39",
            "id_location": "8171",
            "tag": "AZ901",
            "name": {
                "et": "Example er product name",
                "ru": "Example ru product name"
            },
            "location": "Ladu | F34"
        }
    ],
    "shipping_data": {
        "data": {
            "name": "Ülle Sildever",
            "phone": " 372 999999",
            "deliveryNr": "99999-99999",
            "terminal": "124",
            "checked": "defDelivery",
            "email": "",
            "comment": "",
            "COD_Sum": "",
            "barcode": "99999999999999",
            "reference": "99999-99999"
        },
        "barcode": "4216100028820985",
        "data_file": null
    }
}
</pre>

**POST** - Converts reservation into shipment

Path: {Your_Domain}/api/shipments

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A reservation id | Yes |



Example request body:
<pre>
{
	"id": "5841"
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 800 | Reservation id not supplied. |
| 801 | Reservation id not found. |

**DELETE** - Cancels shipment

Path: {Your_Domain}/api/shipments

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A shipment id | Yes |
| products | Array | An array of products where each key represents a product tag or its id from shipment (Not product id itself) | No |

Example request body:
<pre>
This will cancel shipment with id 5844.
{
    "id": "5844"
}

This will cancel only product with its shipment id of 7711 from shipment with id 5828. The shipment itself
will not be deleted unless the product deleted was not the last one in it.
{
    "id": "5828",
    "products": ["7711"]
}

This will cancel only product with tag AZ041 from shipment with id 5828. The shipment itself
will not be deleted unless the product deleted was not the last one in it.
{
    "id": "5828",
    "products": ["AZ041"]
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 900 | No shipment id supplied. |
| 901 | Could not get product in shipment by its tag for `$value`. |
| 902 | There is on or multiple products with this tag for `$value`. Please contact administrator. |
| 903 | Shipment status does not allow cancellation. |


#### Shipments data

| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | Yes |
| PUT | No |
| DELETE | No |

<b>GET</b> - Returns shipment and payment data
Path : {Your_Domain}/api/shipments/data

You need to supply "id" which value represents shipment ID.

Example response for Smartpost type: 
<pre>
{
    "shipment_data": {
        "id_status": "3",
        "data": {
            "name": "Customer name",
            "phone": "9999999",
            "deliveryNr": "",
            "terminal": "190",
            "checked": "defDelivery",
            "email": "",
            "comment": "",
            "COD_Sum": "",
            "barcode": "4216100029191734",
            "reference": []
        },
        "barcode": "4216100029191734",
        "data_file": null
    },
    "payment_data": {
        "data": {
            "cash": "17.99",
            "card": "0.00",
            "ostja": "",
            "tellimuseNr": "",
            "mode": "Bigshop",
            "id_cart": "5819",
            "shipmentID": "5819"
        }
    }
}
</pre>
**Errors**

| Code | Message |
| :---: | :---- | 
| 1000 | No shipment id supplied. |

**POST** - Inserts payment and shipment data

Path: {Your_Domain}/api/shipments/data

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | Integer | A shipment id | Yes |
| id_type | Integer | A type of shipment | Yes |
| barcode | Boolean | Will send request to Smartpost and return barcode | No |
| shipment_data | Array | An array of shipment data  | Yes |
| payment_data | Array | An array of payment data  | Yes |


'shipment_data' for Smartpost:

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| name | String | A clients name | Yes |
| phone | String | A clients phone number | Yes |
| shipmentNr | String | External shipment number (can be left empty) | Yes |
| terminal | Integer | Id of Smartpost parcel terminal | Yes |
| smartpost_type | Integer | Id of Smartpost delivery type  | Yes |
| smartpost_COD_sum | Float/Double with 2 decimal places | Cash on delivery price (is required if you choose 'smartpost_type' with id 2) | No |
| email | String | A clients email | No |
| comment | String |  | No |

'shipment_data' for Venipak:

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| name | String | A clients name | Yes |
| address | String | A clients address | Yes |
| postcode | String | A clients address postcode | Yes |
| housenr | Integer | A clients house number | Yes |
| barcode | Integer | Venipak barcode  | Yes |
| phone | String | A clients phone number | Yes |
| email | String | A clients email | Yes |

While submitting Venipak values can be empty, but the keys are mandatory.


'shipment_data' for Pickup:

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| phone | String | A clients phone number | Yes |


'payment_data':

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| card | Integer (1 or 0) | If 1 - 100% of payment will be registered as card payment otherwise cash | Yes |
| mode | String | Supported values "Bigshop", "Minuvalik", "Shoppa", "Osta" | Yes |
| client | String | Will only be applied if mode value is equal to "Bigshop", if empty "Eraisik" will be inserted | No |
| shipmentNr | String | External shipment number | No |


Example request body:
<pre>
{
    "id": "5845",
    "id_type": "1",
    "barcode": true,
    "shipment_data": {
        "name": "Aleksandr",
        "phone": "56545454",
        "shipmentNr": "",
        "terminal": "190",
        "smartpost_type": "1"
    },
    "payment_data": {
        "card": 1,
        "mode": "Bigshop",
        "client": "Clients name",
        "shipmentNr": "123"
    }
}
</pre>

**Errors**

| Code | Message |
| :---: | :---- | 
| 1100 | Shipment id not submitted. |
| 1101 | Shipment type id not supported. |
| 1102 | Shipment type id not submitted. |
| 1103 | Unable to get cart sum. |
| 1104 | `some-key` key is missing, check your request. |
| 1105 | Unknown 'smartpost_type. |
| 1106 | 'smartpost_COD_sum' not found. |
| 1107 | Shipment id not found. |
| 1108 | Shipment status does not allow data change. |


#### Supplier quantity and price sync
| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | No |
| PUT | Yes |
| DELETE | Yes |


**GET** - Returns skus by a supplier name

Path: {Your_Domain}/api/SyncSupplier

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| supplier_name | String | A supplier name | Yes |

| Code | Message |
| :---: | :---- | 
| 1300 | Supplier name either empty or not supplied. |

**PUT** - Updates quantity and price of product by supplier SKU

Path: {Your_Domain}/api/SyncSupplier

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| supplier_sku | String | A supplier SKU | Yes |
| supplier_name | String | A supplier name | Yes(if price supplied) |
| supplier_url | String | A supplier url in case supplier data does not exist in the database | No |
| warehouse_id | Integer | A warehouse id | Yes(if qty supplied) |
| qty | Integer | Quantity of product  | No |
| price | Float/Double with 2 decimal places | Supplier's price (Needed if supplier data does not exist in the database) | No |
| barcodes | Array | Array of barcodes in case supplier data does not exist in the database | No |
| product_name | String | A product name will be used to find product if not found by barcode and sku  | No |
| brand | String | A product brand (new will be created if none exist with same name)   | No |

Example request body:
<pre>
{
    "supplier_sku": "D-213211",
    "supplier_name": "Dreamlove",
    "supplier_url": "url",
    "warehouse_id": "1",
    "qty": "12",
    "price": "55.22",
    "barcodes": ["2132132132131"],
    "product_name": ""
}
</pre>

| Code | Message |
| :---: | :---- | 
| 1200 | Supplier SKU either empty or not supplied. |
| 1201 | Supplier name either empty or not supplied. |
| 1202 | Warehouse id either empty or not supplied. |
| 1203 | No product found by SKU, no product found by barcode. |
| 1204 | No product found by SKU, barcodes either empty or not supplied. |
| 1205 | No price supplied hence supplier data cannot be inserted. |
| 1206 | No url supplied hence supplier data cannot be inserted. |
| 1207 | No product found by SKU, by barcode and name. |

**DELETE** - Deletes the suppliers warehouse

Path: {Your_Domain}/api/SyncSupplier

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| supplier_sku | String | A supplier SKU | Yes |
| supplier_name | String | A supplier name | Yes |

Example request body:
<pre>
{
    "supplier_sku": "D-213211",
    "supplier_name": "Dreamlove"
}
</pre>

| Code | Message |
| :---: | :---- | 
| 1400 | Supplier name either empty or not supplied. |
| 1401 | Supplier SKU either empty or not supplied. |
| 1402 | Error wile processing delete query. |

#### Product stock locations
| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | No |
| PUT | No |
| DELETE | No |


**GET** - Returns locations and quantity of items in it for a product.

Path: {Your_Domain}/api/product/locations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | String | A product id | Yes* |
| reference | String | A product reference | Yes* |

`* - one of these parameters should be passed`

| Code | Message |
| :---: | :---- | 
| 1500 | No product id or reference supplied. |
| 1501 | Query error. |
| 1502 | No results retrieved. |


#### Product translations
| Method | Allowed |
| :----: | :-----: | 
| GET | Yes |
| POST | No |
| PUT | Yes |
| DELETE | No |


**GET** - Returns product translations for all available languages.

Path: {Your_Domain}/api/product/translations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| tag | String | A product tag | Yes* |
| ean | String | A product ean code | Yes* |

`* - one of these parameters should be passed`

| Code | Message |
| :---: | :---- | 
| 1600 | No product identifier. |
| 1601 | No product found. |

**PUT** - Updates product translations

Path: {Your_Domain}/api/product/translations

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| tag | String | A product tag | Yes* |
| ean | String | A product ean code | Yes* |
| name | Array | An array of product names where key is language code and value is translation | No |
| description | Array | An array of product descriptions where key is language code and value is translation | No |

Example request body:
<pre>
{
    "ean": "55555555",
    "name": {
        "et": "Est name"
    },
    "description": {
        "et": "Est desc",
        "ru": "Rus desc"
    }
}
</pre>

| Code | Message |
| :---: | :---- | 
| 1700 | No product identifier. |
| 1701 | No product found. |


#### Product translations
| Method | Allowed |
| :----: | :-----: | 
| GET | No |
| POST | No |
| PUT | Yes |
| DELETE | No |

**PUT** - Synchronizes product with prestashop

Path: {Your_Domain}/api/product/sync

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| id | String | A product id | Yes |

Example request body:
<pre>
{
    "id": "1232"
}
</pre>

| Code | Message |
| :---: | :---- | 
| 1800 | No product id supplied. |

#### Product creation
| Method | Allowed |
| :----: | :-----: | 
| GET | No |
| POST | Yes |
| PUT | No |
| DELETE | No |

**POST** - Creates a product

Path: {Your_Domain}/api/product

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| prefix | String | A product tag prefix | Yes |
| tag | String | A product tag (can be left with as prefix+001 the API will calculate itself the suitable tag) | Yes |
| actPrice | Float/Double with 2 decimal places | A main supplier price | Yes |
| override | Boolean (0 or 1) | Custom margin toggle (Default 0) | No |
| marginPercent | Float/Double with 2 decimal places | A product margin as percentage (Default 0.0) | No |
| marginNumber | Float/Double with 2 decimal places | A product margin as number (Default 0.0) | No |
| width | Float/Double with 2 decimal places | A product width in mm (Default 0.0) | No |
| height | Float/Double with 2 decimal places | A product height in mm (Default 0.0) | No |
| depth | Float/Double with 2 decimal places | A product depth in mm (Default 0.0) | No |
| weight | Integer | A product weight in g (Default 0) | No |
| category | Integer | A product category ID | Yes |
| ean | Array | A list of product ean codes | No |
| name | Array | A 2d array of product names where key is language id (Integer or String equivalent) and value is its name| Yes |
| supplier | Array | A product supplier data | No |
| description | Array | A 2d array of product names where key is language id (String variant only) and value is its description | No |
| images | Array | A list of product images. Each image list should contain string "new" as first element and base64 code as second. Base64 should be without MIME Content-type. | No |

Suppliers array

| Key | Value type | Comment | Mandatory |
| :--- | :--- | :--- | :--- |
| name | String | A supplier name | Yes*1 |
| url | String | A supplier url | Yes*1 |
| price | Float/Double with 2 decimal places | A supplier price | Yes*1 |
| sku | String | A supplier sku | Yes*1 |
| recPrice | Float/Double with 2 decimal places | A supplier recommended price | Yes |
| idTypeLocation | Integer | A supplier location id | Yes*2 |
| nameLocation | String | A supplier warehouse location name | Yes*2 |
| quantity | Integer | A supplier warehouse quantity | Yes*2 |

*1 All from these group should exist in the request to insert supplier data\
*2 All from these group should exist in the request to insert supplier location


Example request body:
<pre>
{
  "prefix":"LT",
  "tag":"LT001",
  "actPrice":0.97,
  "override":0,
  "marginPercent":0.0,
  "marginNumber":0.0,
  "width":115.0,
  "height":180.0,
  "depth":10.0,
  "weight":35,
  "category":328,
  "ean":["4719855241358"],
  "name":{
    "1":"",
    "en":""
    },
  "supplier":{
    "name":"Dreamlove",
    "url":"_SupplierURL_",
    "price":0.97,
    "recPrice":13.21,
    "sku":"D-221989",
    "idTypeLocation":1,
    "nameLocation":"Dreamlove ladu",
    "quantity":2
    },
  "description":{
    "ru":"",
    "et":"",
    "lt":"",
    "en":"",
    "lv":"",
    "FB":""
    },
  "images":[
    ["new","base64 image"],["new","base64 image"]
  ]
}
</pre>

| Code | Message |
| :---: | :---- | 
| 1900 | Category not supplied. |
| 1901 | Prefix not supplied. |
| 1902 | Tag not supplied. |
| 1903 | actPrice not supplied. |
| 1904 | Name not supplied. |

<b>!!!WARNING!!!</b><br>
<b>The documentation below is deprecated. It still can be used but will be removed on the project release.</b>
[Old documentation](old-docs.md)