# Warehouse Management System API documentation

#### Product reservation
<p>
Creates a new reservation.<br>
Base URL: {Domain}/api/reserve.php
</p>
<pre>
Query: 	username 	(cp username)                   *
        password 	(cp password)                   *
        data		(reservation data, JSON string) *

</pre>
<p>
Data can be sent using both GET and POST.
  </p>
<pre>
Example JSON data:
{
  "note": "Andrei loh",
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
<table>
  <tr>
    <td>Key</td>
    <td>Value type</td>
    <td>Comment</td>
    <td>Mandatory</td>
  </tr>
  <tr>
    <td>note</td>
    <td>String</td>
    <td>A reservation comment, value can be left empty</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>quantity</td>
    <td>Integer</td>
    <td>Product quantity</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>price</td>
    <td>Float/Double with 2 decimal places</td>
    <td>overall product price (price per piece*quantity)</td>
    <td>No</td>
  </tr>
  <tr>
    <td>basePrice</td>
    <td>Float/Double with 2 decimal places</td>
    <td>price per piece</td>
    <td>No</td>
  </tr>
 </table>
<p>
  If price is not supplied it will be either calculated from supplied basePrice or from first platfrom sell price.<br>
  If basePrice is not supplied it will be either calculated from supplied price or retrieve from DB (first platfrom sell price)
</p>

#### Reservation cancellation
<p>
  Cancels a reservation with supplied id while restoring product quantities.<br>
  Base URL: {Domain}/api/remove_reservation.php
</p>

<pre>
Query:  username  (cp username, string)     *
        password  (cp password, string)     *
        id        (reservation id, integer) *
</pre>
Data can be sent using both GET and POST.

#### Perform a sale
<p>
Performs a sale for selected cart and specified platform.<br>
Base URL: {Domain}/api/performSale.php  
</p>
<pre>
Query:  username  (cp username, string)     *
        password  (cp password, string)     *
        data      (sale data, JSON string)  *
</pre>
<p>
  Data can be sent using both GET and POST.
</p>

<pre>
Example JSON data:
{
  "card": 1,
  "mode": "Bigshop",
  "ostja": "Big Shaq",
  "tellimuseNr": "123",
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
<table>
  <tr>
    <td>Key</td>
    <td>Value type</td>
    <td>Comment</td>
    <td>Mandatory</td>
  </tr>
  <tr>
    <td>card</td>
    <td>Integer (1 or 0)</td>
    <td>If 1 - 100% of payment will be registered as card payment otherwise cash</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>mode</td>
    <td>String</td>
    <td>Supported values "Bigshop", "Minuvalik", "Shoppa", "Osta"</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>ostja</td>
    <td>String</td>
    <td>Will only be applied if mode value is equal to "Bigshop", if empty "Eraisik" will be inserted</td>
    <td>No</td>
  </tr>
  <tr>
    <td>tellimuseNr</td>
    <td>String</td>
    <td></td>
    <td>No</td>
  </tr>
  <tr>
    <td>quantity</td>
    <td>Integer</td>
    <td>Product quantity</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>price</td>
    <td>Float/Double with 2 decimal places</td>
    <td>Overall product price (price per piece*quantity)</td>
    <td>No</td>
  </tr>
  <tr>
    <td>basePrice</td>
    <td>Float/Double with 2 decimal places</td>
    <td>Price per piece</td>
    <td>No</td>
  </tr>
 </table>
 
#### Confirm a reservation
<p>
Base URL: {Domain}/api/reservationConfirm.php
</p>
<pre>
Query:  username  (cp username, string)           *
        password  (cp password, string)           *
        data      (reservation data, JSON string) *
</pre>
<p>
  Data can be sent using both GET and POST.
</p>
<pre>
Example JSON data (Full reservation confirm):
{
  "card": 1,
  "mode": "Bigshop",
  "ostja": "Big Shaq",
  "tellimuseNr": "123",
  "reservationID": 22
}
</pre>
<pre>
Example JSON data (Confirm selected products):
{
  "card": 1,
  "mode": "Bigshop",
  "ostja": "Big Shaq",
  "tellimuseNr": "123",	
  "id": 15,
  "products": ["AZ020", "AZ041"]
}
</pre>

<table>
  <tr>
    <td>Key</td>
    <td>Value type</td>
    <td>Comment</td>
    <td>Mandatory</td>
  </tr>
  <tr>
    <td>card</td>
    <td>Integer (1 or 0)</td>
    <td>If 1 - 100% of payment will be registered as card payment otherwise cash</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>mode</td>
    <td>String</td>
    <td>Supported values "Bigshop", "Minuvalik", "Shoppa", "Osta"</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>ostja</td>
    <td>String</td>
    <td>Will only be applied if mode value is equal to "Bigshop", if empty "Eraisik" will be inserted</td>
    <td>No</td>
  </tr>
  <tr>
    <td>tellimuseNr</td>
    <td>String</td>
    <td></td>
    <td>No</td>
  </tr>
  <tr>
    <td>id</td>
    <td>Integer</td>
    <td>Reservation ID</td>
    <td>Yes</td>
  </tr>
  <tr>
    <td>products</td>
    <td>Array of strings</td>
    <td>Contains product tags, if not supplied whole reservation will be confirmed</td>
    <td>No</td>
  </tr>
 </table>
 
 #### Edit reservation comment
<p>
Base URL: {Domain}/api/editReservation.php
</p>
<pre>
Query:  username  (Cp username, String)                 *
        password  (Cp password, String)                 *
        comment   (New reservation comment, String)     *
        id        (Reservation ID, Integer)             *
</pre>
