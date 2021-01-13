# Warehouse Management System API documentation

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

<b>!!!WARNING!!!</b><br>
<b>The documentation below is deprecated. It still can be used but will be removed on the project release.</b>
[Old documentation](old-docs.md)