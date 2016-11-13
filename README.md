# Installation

* Create schema
```
php bin/console doctrine:schema:update --force
```
* Create sample data
``` 
php bin/console doctrine:fixtures:load
```
* Run tests
``` 
phpunit
```

# API documentation
API is RestFull, on **success**, http code 200 and json object in response body is returned. If object is not found, http code 404 is returned. In case of **error**, http code 500 is returned and more info is provided in response body.


## Products

Get product list page, default {page} = 1, method: **GET**
``` 
/api/products/{page}
```
Response example:
``` json 
{
    products: [
        {id: 1, title: "test", price: 1.99},
        {id: 2, title: "test 2", price: 2.99}
    ],
    pager: {
        count: 2,
        perPage: 20
    }
}
```


Create product, method: **POST**, required parameters: **title** type string, **price** type decimal(10,2)
``` 
/api/products
```
Response example (returns new product):
``` json 
{
    id: 1, 
    title: "test", 
    price: 1.99
}
```


Update product, method: **PUT**, optional parameters: **title** type string, **price** type decimal(10,2)
``` 
/api/products
```
Response example (returns updated product):
``` json 
{
    id: 1, 
    title: "test", 
    price: 1.99
}
```

Delete product, method: **DELETE**, {id} product id
``` 
/api/products/{id}
```
Returns empty response


## Cart

Get cart method: **GET**, required {cartId} cart id
``` 
/api/cart/{cartId}
```
Response example:
``` json 
{
    id: 1, 
    items: [
        {id : 1, productId : 1, productTitle => "test", productPrice : 1.60},
        {id : 2, productId : 2, productTitle => "test 2", productPrice : 1.40}
    ], 
    itemsSum: 3.00,
    itemsCount: 2
}
```

Create cart, method: **POST**
``` 
/api/cart
```
Response example:
Similarly to get cart method

Add product to cart, method: **PUT**, required {cartId} cart id, required {productId} product id
``` 
/api/cart/{cartId}/product/{productId}
```
Response example:
Similarly to get cart method (returns updated cart)

Remove product from cart, method: **DELETE**, required {cartId} cart id, required {productId} product id
``` 
/api/cart/{cartId}/product/{productId}
```
Response example:
Similarly to get cart method (returns updated cart)

Delete cart, method: **DELETE**, required {cartId} cart id
``` 
/api/cart/{cartId}
```
Returns empty response