---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://sanmaru.test/docs/collection.json)

<!-- END_INFO -->

#Attendance Information


APIs for user's attendance information
<!-- START_9a5f635714377d4e4f73ea4f09960c24 -->
## [GET] Retrieve Attendance

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve users attendances with 3 types: present (hadir), absent (tidak masuk), onleave (izin).

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/attendance?limit=5.+Default%3A+20.&offset=5&start_date=2020-03-11" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/attendance"
);

let params = {
    "limit": "5. Default: 20.",
    "offset": "5",
    "start_date": "2020-03-11",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "date": "2020-03-28",
            "type": "present",
            "reason": null
        },
        {
            "date": "2020-03-27",
            "type": "onleave",
            "reason": "perut sakit"
        },
        {
            "date": "2020-03-26",
            "type": "absent",
            "reason": null
        },
        {
            "date": "2020-03-25",
            "type": "present",
            "reason": null
        },
        {
            "date": "2020-03-24",
            "type": "present",
            "reason": null
        }
    ],
    "meta": {
        "limit": 20,
        "offset": 0,
        "total": 5
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/attendance`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.
    `start_date` |  optional  | start date to query attendance data.

<!-- END_9a5f635714377d4e4f73ea4f09960c24 -->

#Bill Category Information


APIs for bill information
<!-- START_abcdb721e8303b376a835480cd492913 -->
## [GET] Retrieve Bill&#039;s Category List

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve bill categories

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/bill/categories?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/bill/categories"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "Biaya Kelas 2 SMA"
        }
    ],
    "meta": {
        "total": 1
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/bill/categories`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_abcdb721e8303b376a835480cd492913 -->

#Blog Category Information


APIs for Blogs Category information
<!-- START_bc7869afc1391a7115ca3a6d93b4dca4 -->
## [GET] Retrieve Blog Category

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve blog category detail based on slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/blogs/categories/et" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/blogs/categories/et"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "name": "Kegiatan Sekolah",
        "slug": "kegiatan-sekolah",
        "is_active": 1,
        "total_blogs": 1
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/blogs/categories/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the blog category

<!-- END_bc7869afc1391a7115ca3a6d93b4dca4 -->

<!-- START_9cf80464a85d21b2754a5dfae76a14e6 -->
## [GET] Retrieve Blog Category list

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve blog category list, can be filled with limit, and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/blogs/categories?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/blogs/categories"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "name": "Kegiatan Sekolah",
            "slug": null,
            "total_blogs": 1
        },
        {
            "name": "Pengumuman",
            "slug": null,
            "total_blogs": 2
        },
        {
            "name": "Mata Pelajaran",
            "slug": null,
            "total_blogs": 0
        },
        {
            "name": "Seputar Guru",
            "slug": null,
            "total_blogs": 2
        },
        {
            "name": "Tips Belajar",
            "slug": null,
            "total_blogs": 1
        },
        {
            "name": "Berita",
            "slug": null,
            "total_blogs": 7
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 6
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/blogs/categories`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_9cf80464a85d21b2754a5dfae76a14e6 -->

#Blogs Information


APIs for Blogs information
<!-- START_78e0b8253a36c54dee5d7d5b2632d028 -->
## [GET] Retrieve Blog

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve blog detail based on slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/blogs/repellendus" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/blogs/repellendus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "title": "Aksi Asik Guru Nyentrik Masa Kini",
        "short_desc": "aksi guru Santa Maria",
        "slug": "aksi-asik-guru-nyentrik-masa-kini",
        "category": "Seputar Guru",
        "category_slug": "seputar-guru",
        "content": "<p><span style=\"background-color:rgb(255,255,255);color:rgb(99,99,99);\">Jumat, 26 Februari 2021- Asik menarik siapa sih yang tidak suka? Aksi guru nyentrik mampu membuat anak-anak terpesona dan bersemangat dalam mengikuti pembiasaan pagi senam hari itu di chanel youtube SD Santa Maria 2 Sidoarjo. Menjaga kebugaran tubuh di masa pandemi ini bisa kita lakukan salah satunya dengan senam, guna menambah kekebalan imun dalam diri kita. Tak kalah dengan siswa-siswinya guru jaman now juga harus up to date menghadapi siswa-siswi milenial. Dunia Tik Tok sudah menjadi konsumsi publik sehari-hari karena konten-kontenya yang menarik dan kekinian. Dengan bersemangat guru-guru nyentrik ini dengan lihainya memadukan gerakan senam zumba dengan gerakan Tik Tok yang lagi viral. Liak-liuknya gemulai tak kalah dengan seleb Tik Tok. Menurut Ki Hadjar Dewantara (dalam Dantes, 2017), pendidikan merupakan usaha memanusiakan manusia. Untuk bisa mendidik anak menjadi manusia seutuhnya, guru haruslah memahami dunia anak. Bukan anak yang harus memahami dunia guru. Ini kunci penting dalam membuka pola pikir guru kekinian. (Yov)<\/span><\/p>",
        "publish_date": "2021-03-10 12:00:00",
        "published": 1,
        "featured_image": "http:\/\/sanmaru.test\/images\/featured_image\/cdndo_32323739706870796a4c4b6d41.jpeg"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/blogs/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the blog

<!-- END_78e0b8253a36c54dee5d7d5b2632d028 -->

<!-- START_8ce9b35821b279e8fe94fb52ad18a077 -->
## [GET] Retrieve Blogs list

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve blogs list, can be filled with limit, blog category, and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/blogs?limit=5&offset=5&category=kegiatan-sekolah&published=odio" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/blogs"
);

let params = {
    "limit": "5",
    "offset": "5",
    "category": "kegiatan-sekolah",
    "published": "odio",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/blogs`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.
    `category` |  optional  | slug of blog category to be queried.
    `published` |  optional  | published or unpublish blog status. Example 1 / 0

<!-- END_8ce9b35821b279e8fe94fb52ad18a077 -->

#Cart Information


APIs for Cart
<!-- START_8a98c0732ee823c2427f72ce7a01e965 -->
## [POST] Add Product to Cart

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update user's cart

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/cart/add" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"slug":"consectetur","size":"quaerat","quantity":18}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/cart/add"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "slug": "consectetur",
    "size": "quaerat",
    "quantity": 18
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "Cart berhasil diupdate!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/cart/add`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `slug` | string |  required  | product slug
        `size` | string |  required  | product size
        `quantity` | integer |  required  | product quantity
    
<!-- END_8a98c0732ee823c2427f72ce7a01e965 -->

<!-- START_4d3525a594e12fc6677f1018800f5149 -->
## [POST] Remove Product from Cart

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Remove product from user's cart

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/cart/remove" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"slug":"nulla","size":"rem"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/cart/remove"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "slug": "nulla",
    "size": "rem"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "Cart berhasil diupdate!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/cart/remove`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `slug` | string |  required  | product slug
        `size` | string |  required  | product size
    
<!-- END_4d3525a594e12fc6677f1018800f5149 -->

<!-- START_21b67e4d5f5a4a7539b699b093814541 -->
## [POST] Apply Voucher to Cart

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update user's cart voucher

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/cart/voucher" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"voucher":"aliquid"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/cart/voucher"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "voucher": "aliquid"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "Voucher berhasil diupdate!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/cart/voucher`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `voucher` | string/null |  required  | voucher code to be applied
    
<!-- END_21b67e4d5f5a4a7539b699b093814541 -->

<!-- START_50f7c549c1a63d3076fd1e611601cc99 -->
## [POST] Update Cart

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update user's cart content

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/cart/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"details":[{"slug":"non","size":"praesentium","quantity":9}],"voucher":"ad"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/cart/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "details": [
        {
            "slug": "non",
            "size": "praesentium",
            "quantity": 9
        }
    ],
    "voucher": "ad"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "Cart berhasil diupdate!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/cart/update`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `details` | array |  required  | details content of cart  changed.
        `details.*.slug` | string |  required  | product slug
        `details.*.size` | string |  required  | product size
        `details.*.quantity` | integer |  required  | product quantity
        `voucher` | string/null |  optional  | voucher code to be applied
    
<!-- END_50f7c549c1a63d3076fd1e611601cc99 -->

<!-- START_6c1682f4a40c1555254df708bf6b4a71 -->
## [GET] Retrieve User Cart

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve cart data by user

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/cart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/cart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "status": "new_added",
        "grand_total": 120000,
        "discount_total": 100000,
        "grand_total_after_discount": 20000,
        "vouchers": {
            "id": 22,
            "code": "032122KHUSUSPI",
            "rule": "100000",
            "note": null,
            "type": "discount_fixed",
            "usage_limit": 1
        },
        "details": [
            {
                "quantity": 1,
                "total_price": "120000.00",
                "name": "[SMP] Kemeja Serviam Putra",
                "slug": "smp-sby-kemeja-serviam-pa",
                "size": "15",
                "image": "http:\/\/localhost\/images\/product\/5b534d50205342595d204b656d656a61205365727669616d2050417068706351744f4352.jpeg"
            }
        ]
    },
    "meta": {
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/cart`


<!-- END_6c1682f4a40c1555254df708bf6b4a71 -->

#Class Schedule Information


APIs for Class Schedule
<!-- START_56f9be1d9ea21c4b7063b4c5d6023730 -->
## [GET] Retrieve Class Schedule

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve class schedule by user

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/class-schedules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/class-schedules"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "class": "KB A CATHERINE",
            "course": "Matematika",
            "day": "monday",
            "start_time": "08:00:00",
            "end_time": "10:00:00"
        },
        {
            "class": "KB A CATHERINE",
            "course": "Matematika",
            "day": "tuesday",
            "start_time": "10:00:00",
            "end_time": "12:00:00"
        }
    ],
    "meta": {
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/class-schedules`


<!-- END_56f9be1d9ea21c4b7063b4c5d6023730 -->

#Course Assignment Information


APIs for course assignment information
<!-- START_49d5fdb47c40dfef45f57bef2f934c17 -->
## [GET] Retrieve Assignments

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve student's assignments based on active student's course

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/assignments?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/assignments"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "score": 80,
            "course_assignment_id": 1,
            "assignment_name": "Tugas 1 Matematika"
        },
        {
            "score": 85,
            "course_assignment_id": 2,
            "assignment_name": "Tugas 1 Fisika"
        },
        {
            "score": 70,
            "course_assignment_id": 3,
            "assignment_name": "Tugas 2 Fisika"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 3
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/assignments`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_49d5fdb47c40dfef45f57bef2f934c17 -->

#Course Information


APIs for course information
<!-- START_0ec32a5c7dac7b493d908412c6b29324 -->
## [GET] Retrieve Courses

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve student's active courses

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/courses?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/courses"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "name": "Matematika",
            "code": "MX120"
        },
        {
            "name": "Fisika",
            "code": "FX120"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 2
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/courses`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_0ec32a5c7dac7b493d908412c6b29324 -->

#Course Schedule Information


APIs for course's schedule information
<!-- START_a5bfdca57297e7b0374cefa63c6e423f -->
## [GET] Retrieve Course Schedule

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve course schedule per day

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/schedule/nihil/courses" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/schedule/nihil/courses"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "start_time": "08:00:00",
            "day": "monday",
            "name": "Matematika"
        },
        {
            "start_time": "09:00:00",
            "day": "monday",
            "name": "Fisika"
        }
    ],
    "meta": {
        "total": 2
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/schedule/{day}/courses`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `day` |  optional  | day name of the week, in english

<!-- END_a5bfdca57297e7b0374cefa63c6e423f -->

#Course UAS Information


APIs for course's UAS information
<!-- START_78177869262bb5974290e38a572d510a -->
## [GET] Retrieve Student&#039;s UAS

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve student's UAS based on active student's course

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/uas?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/uas"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "uas_score": 90,
            "course_id": 2,
            "course_name": "Fisika"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 1
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/uas`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_78177869262bb5974290e38a572d510a -->

#Course UTS Information


APIs for course UTS information
<!-- START_69ee6a5ab1d17c810638606383ad3c86 -->
## [GET] Retrieve students UTS

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve student's UTS based on active student's course

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/uts?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/uts"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "uts_score": 70,
            "course_id": 1,
            "course_name": "Matematika"
        },
        {
            "uts_score": 80,
            "course_id": 2,
            "course_name": "Fisika"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 2
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/uts`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_69ee6a5ab1d17c810638606383ad3c86 -->

#Event information


APIs for event information
<!-- START_742a1cbd4a274c7269f0db99a704ff41 -->
## [GET] Retrieve Event List

Retrieve events

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/events?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/events"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "title": "event 1",
            "event_time": "2020-03-22 00:00:00",
            "image": "http:\/\/localhost:8000\/img\/default-event.jpg"
        },
        {
            "title": "event 2",
            "event_time": "2020-03-22 00:00:00",
            "image": "http:\/\/localhost:8000\/img\/default-event.jpg"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 2
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/events`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_742a1cbd4a274c7269f0db99a704ff41 -->

<!-- START_f36e77ce83ef3131131753e9591ba60f -->
## [GET] Retrieve Event Detail

Retrieve event detail

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/events/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/events/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "id": 1,
        "title": "event 1",
        "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non pretium velit, eu sodales nibh. Ut nec tincidunt tellus. Quisque dictum auctor eros, in sagittis risus vulputate vel. Quisque sed neque ligula. Nam id congue quam. Suspendisse lobortis eros eu mi vulputate vestibulum. Nullam augue ligula, fringilla nec arcu non, iaculis porta massa. Mauris vestibulum arcu ac mauris tincidunt mollis at a tellus. Sed blandit ac magna a volutpat. Mauris eget urna lorem.\n\n                Nulla ultrices, enim ut dignissim facilisis, libero sem aliquet augue, id convallis ligula augue in ante. Vestibulum lorem urna, ullamcorper faucibus tristique ut, imperdiet non mauris. In bibendum turpis a arcu vestibulum, ac dapibus quam sagittis. Donec nunc lorem, blandit id leo vitae, tempus blandit nisi. Aenean quis molestie nunc. Praesent eleifend sagittis quam in condimentum. Suspendisse in nisi suscipit, pretium est ut, tristique ante. Mauris quis dolor interdum, convallis velit sed, vehicula tortor. Maecenas eleifend commodo lectus, et luctus diam ullamcorper eget. Curabitur porta nunc et dui molestie, non imperdiet risus dictum.\n\n                Morbi et diam sed turpis interdum maximus. Nulla mattis mi est, sit amet hendrerit leo fermentum et. Praesent eget odio et elit volutpat cursus vitae eget felis. Ut fringilla tellus a pulvinar rhoncus. Vivamus in sapien magna. Curabitur a consectetur tortor, eget tincidunt lacus. Suspendisse a lorem odio. Vivamus rutrum ornare tellus vitae gravida.\n\n                Fusce sed velit vestibulum, tempus magna eget, convallis leo. Vivamus volutpat sed lectus vitae consectetur. Vivamus nibh lorem, ultrices eu orci sed, placerat luctus dolor. Vivamus tellus nunc, commodo in nunc vitae, fringilla tincidunt nisl. Suspendisse potenti. Nunc ut diam in dolor facilisis sollicitudin eget quis ligula. Ut ac iaculis ipsum. Praesent felis tellus, faucibus eget bibendum in, sagittis et felis. Donec eget elit lacus. Quisque eget metus consectetur, posuere libero sit amet, accumsan libero. Aliquam tempor urna sem, a ornare augue mollis ac. Donec eu elementum velit.\n\n                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris accumsan commodo venenatis. Aenean ultrices vel magna non tristique. Ut odio est, ultricies et ullamcorper et, porttitor vitae augue. Mauris suscipit posuere lorem in congue. Suspendisse laoreet aliquam metus sed fringilla. Vestibulum pharetra rhoncus enim, ut ultrices elit commodo at. Nam maximus sapien non justo ullamcorper, non volutpat erat facilisis. Praesent venenatis enim quam, sit amet eleifend felis sodales at. Vivamus mollis consequat semper. Etiam finibus posuere tellus in imperdiet. Pellentesque elementum pellentesque nulla quis rutrum.",
        "location": "Sekolah",
        "event_time": "2020-03-22 00:00:00",
        "created_by": 1,
        "last_updated_by": 1,
        "deleted_at": null,
        "created_at": "2020-02-21 09:46:39",
        "updated_at": "2020-02-21 09:46:39",
        "image": "http:\/\/localhost:8000\/img\/default-event.jpg"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/events/{id}`


<!-- END_f36e77ce83ef3131131753e9591ba60f -->

#FAQ Information


APIs for FAQs information
<!-- START_f89449b91c5229df286685bc996819da -->
## [GET] Retrieve Blog Category

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve blog category detail based on slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/faqs/quas" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/faqs/quas"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "name": "Kegiatan Sekolah",
        "slug": "kegiatan-sekolah",
        "is_active": 1,
        "total_blogs": 1
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/faqs/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the blog category

<!-- END_f89449b91c5229df286685bc996819da -->

<!-- START_b8171a4fd016c86f64f8106d911dabd2 -->
## [GET] Retrieve FAQs list

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve FAQs list, can be filled with limit, and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/faqs?limit=5&offset=5&published=1+%2F+0" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/faqs"
);

let params = {
    "limit": "5",
    "offset": "5",
    "published": "1 / 0",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "title": "Lory. Alice replied thoughtfully. They have their tails.",
            "slug": "lory-alice-replied-thoughtfully-they-have-their-tails",
            "content": "aasda",
            "answer": "asdasd",
            "category": "web-school",
            "publish_date": "2021-07-10 00:00:00",
            "published": 1
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 6
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/faqs`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.
    `published` |  optional  | published or unpublished faq status.

<!-- END_b8171a4fd016c86f64f8106d911dabd2 -->

#Login User


APIs to login to the api service
<!-- START_737ccfb685073c55ab973d56082898ff -->
## [POST] sending email to set new password

Feature to help reset password by filling email to API

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/forget-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"email\/username":"quod"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/forget-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "email\/username": "quod"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "silahkan cek email yang terdaftar dan klik tautan untuk membuat password baru"
}
```
> Example response (401):

```json
{
    "message": {
        "username": [
            "username wajib diisi."
        ]
    }
}
```
> Example response (401):

```json
{
    "message": "Email\/Username is Not Found"
}
```

### HTTP Request
`POST api/forget-password`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email/username` | string |  optional  | user's email or username
    
<!-- END_737ccfb685073c55ab973d56082898ff -->

<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## [POST] Login to get token

Logging in with username and password to get user's token to be used in authenticated API

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "token": "3Uh3taUYBYkBf47FCzX6dIoS126LGtKjOnQyECjvhXKqhPFD82l42eZPDU3J",
    "user": {
        "id": 3,
        "username": "student1",
        "email": "student1@a.b.c",
        "mobile_phone": 6282122329293,
        "type": "siswa",
        "status": "active",
        "register_token": null,
        "deleted_at": null,
        "created_at": "2020-02-21 09:46:39",
        "updated_at": "2020-02-29 21:28:22"
    }
}
```
> Example response (401):

```json
{
    "message": {
        "password": [
            "password wajib diisi."
        ]
    }
}
```
> Example response (401):

```json
{
    "message": "Email\/Username is Not Found"
}
```
> Example response (401):

```json
{
    "message": "Wrong Password"
}
```

### HTTP Request
`POST api/login`


<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

#Order Information


APIs for Order
<!-- START_e4d117f402f44fbcf54e34bf6e477b9e -->
## [POST] Post payment image of User Order Transaction

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
upload payment image of user order transaction

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/orders/1/upload-payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"image":"ipsam"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/orders/1/upload-payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "image": "ipsam"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "meta": {
        "message": "bukti pembayaran berhasil diunggah",
        "data": {
            "path_upload": "payment_image\/323238397068703430422e746d70.jpeg",
            "path_url": "http:\/\/sanmaru.test\/images\/payment_image\/323238397068703430422e746d70.jpeg",
            "path": "http:\/\/sanmaru.test\/images\/payment_image\/323238397068703430422e746d70.jpeg",
            "filename": "323238397068703430422e746d70.jpeg"
        },
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/orders/{order_id}/upload-payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `image` | file |  required  | payment image file
    
<!-- END_e4d117f402f44fbcf54e34bf6e477b9e -->

<!-- START_15e0b479b7f6786e25095f8769b4ffdd -->
## [POST] Cancel User Order Transaction

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Cancel user order transaction

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/orders/1/cancel" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/orders/1/cancel"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "meta": {
        "message": "order berhasil ditabalkan",
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/orders/{order_id}/cancel`


<!-- END_15e0b479b7f6786e25095f8769b4ffdd -->

<!-- START_94db31d6f997b219bf91b88185da2300 -->
## [GET] Retrieve User Order Transaction

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve cart data by user

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "id": 1211,
        "invoice_no": "21030820001175",
        "status": "confirmed",
        "payment_image": "http:\/\/localhost\/images\/payment_image\/cdndo_7068706a716b534a49.jpeg",
        "pickup_status": "not_pickup",
        "pickup_date": null,
        "pickup_image": null,
        "payment_confirmed_date": "2021-06-02 01:46:06",
        "payment_confirmed_mail_sent": null,
        "details": [
            {
                "quantity": 1,
                "total_price": 90000,
                "name": "[SD] Kemeja Serviam",
                "slug": "sd-sby-kemeja-serviam",
                "price": 90000,
                "size": "11",
                "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b656d656a61205365727669616d70687058364c655253.jpeg"
            },
            {
                "quantity": 1,
                "total_price": 95000,
                "name": "[SD] Kemeja Nasional",
                "slug": "sd-sby-kemeja-nasional",
                "price": 95000,
                "size": "11",
                "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b656d656a61204e6173696f6e616c706870716a53626d4a.jpeg"
            },
            {
                "quantity": 2,
                "total_price": 50000,
                "name": "[SD] Kaos Kaki",
                "slug": "sd-sby-kaos-kaki",
                "price": 25000,
                "size": "19-22",
                "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b616f73204b616b6970687059696e587668.jpeg"
            }
        ],
        "vouchers": null,
        "grand_total": 750000,
        "discount_total": 0,
        "grand_total_after_discount": 750000
    },
    "meta": {
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/orders/{order_id}`


<!-- END_94db31d6f997b219bf91b88185da2300 -->

<!-- START_285c87403b6cfdebe26bc357f22e870f -->
## [POST] Post Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Make new order

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"details":[{"slug":"quia","size":"aliquam","quantity":18}]}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "details": [
        {
            "slug": "quia",
            "size": "aliquam",
            "quantity": 18
        }
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "sukses menambahkan order baru!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/orders`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `details` | array |  required  | details content of cart  changed.
        `details.*.slug` | string |  required  | product slug
        `details.*.size` | string |  required  | product size
        `details.*.quantity` | integer |  required  | product quantity
    
<!-- END_285c87403b6cfdebe26bc357f22e870f -->

<!-- START_f9301c03a9281c0847565f96e6f723de -->
## [GET] Retrieve User Orders Transaction

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve cart data by user

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "id": 1211,
            "invoice_no": "21030820001175",
            "status": "confirmed",
            "payment_image": "http:\/\/localhost\/images\/payment_image\/cdndo_7068706a716b534a49.jpeg",
            "pickup_status": "not_pickup",
            "pickup_date": null,
            "pickup_image": null,
            "payment_confirmed_date": "2021-06-02 01:46:06",
            "payment_confirmed_mail_sent": null,
            "details": [
                {
                    "quantity": 1,
                    "total_price": 90000,
                    "name": "[SD] Kemeja Serviam",
                    "slug": "sd-sby-kemeja-serviam",
                    "price": 90000,
                    "size": "11",
                    "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b656d656a61205365727669616d70687058364c655253.jpeg"
                },
                {
                    "quantity": 1,
                    "total_price": 95000,
                    "name": "[SD] Kemeja Nasional",
                    "slug": "sd-sby-kemeja-nasional",
                    "price": 95000,
                    "size": "11",
                    "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b656d656a61204e6173696f6e616c706870716a53626d4a.jpeg"
                },
                {
                    "quantity": 2,
                    "total_price": 50000,
                    "name": "[SD] Kaos Kaki",
                    "slug": "sd-sby-kaos-kaki",
                    "price": 25000,
                    "size": "19-22",
                    "image": "http:\/\/localhost\/images\/product\/5b5344205342595d204b616f73204b616b6970687059696e587668.jpeg"
                }
            ],
            "vouchers": null,
            "grand_total": 750000,
            "discount_total": 0,
            "grand_total_after_discount": 750000
        }
    ],
    "meta": {
        "status": 200
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/orders`


<!-- END_f9301c03a9281c0847565f96e6f723de -->

#Product Category Information


APIs for product category information
<!-- START_5c12eff4e00a85b5577a440ef9aa127f -->
## [GET] Retrieve Product Categories

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve product's categories, can be filled with limit and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/categories?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/categories"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "slug": "pakaian-pria",
            "name": "Pakaian Pria"
        }
    ],
    "meta": {
        "total": 1
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/products/categories`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_5c12eff4e00a85b5577a440ef9aa127f -->

<!-- START_cd535061ce269afe48c49129fd3227f8 -->
## [GET] Retrieve Product Category Detail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve product category detail based on slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/categories/aliquam" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/categories/aliquam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "id": 1,
        "name": "Pakaian Pria",
        "description": "Ini barang-barang pakaian untuk pria",
        "slug": "pakaian-pria",
        "deleted_at": null,
        "created_at": "2020-02-21 09:46:39",
        "updated_at": "2020-02-21 09:46:39"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/products/categories/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the product category

<!-- END_cd535061ce269afe48c49129fd3227f8 -->

#Product Information


APIs for product information
<!-- START_91daff6ce81359ce452e232cf1893d23 -->
## [GET] Retrieve Product List

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve products list, can be filled with category's slug, limit, and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "name": "Satu Set Kemeja Batik SMP",
            "slug": "satu-set-kemeja-batik-smp",
            "details": [
                {
                    "size": "S",
                    "stock": 100,
                    "price": "10000.00"
                },
                {
                    "size": "M",
                    "stock": 100,
                    "price": "10000.00"
                }
            ],
            "image": "http:\/\/localhost:8000\/img\/default-seragam.jpg"
        },
        {
            "name": "Kemeja Coklat SMA",
            "slug": "kemeja-coklat-sma",
            "details": [
                {
                    "size": "S",
                    "stock": 100,
                    "price": "10000.00"
                }
            ],
            "image": "http:\/\/localhost:8000\/img\/default-seragam.jpg"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 2
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/products/{categorySlug?}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the product category
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_91daff6ce81359ce452e232cf1893d23 -->

<!-- START_6b12ff84fd809048dbe2e2d28dbab96f -->
## [GET] Retrieve Product Detail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve product detail based on product slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/detail/minima" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/detail/minima"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "name": "Kemeja Coklat SMA",
        "slug": "kemeja-coklat-sma",
        "weight": 300,
        "merk": "Purnama",
        "detail": [
            {
                "stok": 10,
                "price": "1000.00",
                "size": "S"
            },
            {
                "stok": 120,
                "price": "1000.00",
                "size": "M"
            }
        ],
        "type_name": "Kemeja SMP",
        "category_name": "Pakaian Pria",
        "image": "http:\/\/localhost:8000\/img\/default-seragam.jpg"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/products/detail/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the product category

<!-- END_6b12ff84fd809048dbe2e2d28dbab96f -->

#Product Type Information


APIs for product type information
<!-- START_7ec27806ffb75fca7ea2783cd2479f68 -->
## [GET] Retrieve Product Types

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve product's types, can be filled with limit and offset

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/types?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/types"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "slug": "kemeja-smp",
            "name": "Kemeja SMP"
        }
    ],
    "meta": {
        "total": 1
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/products/types`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_7ec27806ffb75fca7ea2783cd2479f68 -->

<!-- START_79037e58750b4e23756633c424817119 -->
## [GET] Retrieve Product Type Detail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve product type detail based on slug

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/products/types/nam" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/products/types/nam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "id": 1,
        "name": "Kemeja SMP",
        "description": "Ini barang-barang kemeja untuk SMP",
        "slug": "kemeja-smp",
        "deleted_at": null,
        "created_at": "2020-02-21 09:46:39",
        "updated_at": "2020-02-21 09:46:39"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/products/types/{slug}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `slug` |  optional  | slug name of the product types

<!-- END_79037e58750b4e23756633c424817119 -->

#User Actions


APIs for user's actions
<!-- START_617091298b08daf684a72b8961aab62e -->
## [POST] Register User

Update user's password

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/register/quis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"name":"Budi","email":"budi@anduk.com","mobile_phone":82132232323,"password":"newpassword","password_confirmation":"newpassword"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/register/quis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "name": "Budi",
    "email": "budi@anduk.com",
    "mobile_phone": 82132232323,
    "password": "newpassword",
    "password_confirmation": "newpassword"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "email": "budi@anduk.com",
        "username": "budi",
        "type": "siswa",
        "mobile_phone": "6282132232323",
        "register_token": "$2y$10$R3e1GvblaIP4GmHAdQRXm.Awn90Gx9SDKRXQx\/dI9qkUWdM0uK1BG",
        "status": "active",
        "updated_at": "2020-03-19 18:01:08",
        "created_at": "2020-03-19 18:01:08",
        "id": 5
    }
}
```
> Example response (422):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Nama harus diisi"
        ],
        "email": [
            "Email harus diisi"
        ],
        "mobile_phone": [
            "Nomor telepon harus diisi"
        ],
        "password": [
            "Password baru harus diisi"
        ]
    }
}
```

### HTTP Request
`POST api/register/{type}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `type` |  required  | type of user: guru, siswa, ppdb, vendor
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  optional  | user's name.
        `email` | string |  optional  | user's name.
        `mobile_phone` | integer |  optional  | user's mobile phone number.
        `password` | string |  optional  | new user's password.
        `password_confirmation` | new |  optional  | password confirmation.
    
<!-- END_617091298b08daf684a72b8961aab62e -->

<!-- START_d081c59f77b96ebd1787ec155e434e4b -->
## [POST] Update Password

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update user's password

> Example request:

```bash
curl -X POST \
    "http://sanmaru.test/api/user/update-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}" \
    -d '{"old_password":"password","password":"newpassword","password_confirmation":"newpassword"}'

```

```javascript
const url = new URL(
    "http://sanmaru.test/api/user/update-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

let body = {
    "old_password": "password",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "message": "Password update is successful!"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`POST api/user/update-password`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `old_password` | string |  optional  | user's password to be changed.
        `password` | string |  optional  | new user's password.
        `password_confirmation` | new |  optional  | password confirmation.
    
<!-- END_d081c59f77b96ebd1787ec155e434e4b -->

<!-- START_fc7fbf833bb6e45195cabe758b14a6c0 -->
## [GET] Retrieve Home Profile

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve user's profile for home page

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/user/home-profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/user/home-profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/user/home-profile`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `billId` |  optional  | id of bill wanna be shown

<!-- END_fc7fbf833bb6e45195cabe758b14a6c0 -->

<!-- START_a4251b7143964e3f7d9fb181a7b86ba5 -->
## [GET] Retrieve Profile Complete

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve user's profile for profile page

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/user/profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/user/profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user": {
        "username": "dandy",
        "email": "dandyfirmansyah1998@gmail.com",
        "mobile_phone": 6282113843687,
        "type": "admin",
        "status": "active"
    },
    "student": {
        "nis": "9999",
        "name": "Dandy Firmansyah",
        "email": "dandyfirmansyah@email.com",
        "mobile_phone": "08123365511",
        "address": "Malang Sawojajar",
        "unit_name": "Unit 1",
        "class_name": "class 1",
        "payment_agreement_name": "Payment Agreements 1",
        "school_year": 2020,
        "gender": "male",
        "origin_school": "Sekolah Dulu",
        "ttl": "Malang, 1 Agustus 2000",
        "religion": "Kristen",
        "number_of_siblings": "2"
    },
    "parents": [
        {
            "name": "Bapak Bro",
            "place_of_birth": "Lumajang",
            "date_of_birth": "2020-02-05",
            "address": "Malang",
            "city": "Malang",
            "region": "Jawa Timur",
            "country": "Indonesia",
            "religion": "Islam",
            "phone": 871233222,
            "job": "Pengusaha",
            "card_identity": null,
            "type": "father"
        },
        {
            "name": "Ibu Bro",
            "place_of_birth": "Malang",
            "date_of_birth": "1978-01-01",
            "address": "Malang",
            "city": "Malang",
            "region": "Jawa Timur",
            "country": "Indonesia",
            "religion": "Islam",
            "phone": 6287912332,
            "job": "IRT",
            "card_identity": null,
            "type": "mother"
        }
    ]
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/user/profile`


<!-- END_a4251b7143964e3f7d9fb181a7b86ba5 -->

#User Bill Information


APIs for bill information
<!-- START_56f3aa8fdde5a1d1308cc5c1ee8fdf24 -->
## [GET] Retrieve User&#039;s Bill List

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve user's bill based on bill category

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/bill/1?limit=5&offset=5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/bill/1"
);

let params = {
    "limit": "5",
    "offset": "5",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "Uang Bulanan",
            "due_date": "2020-03-21",
            "amount": 500000,
            "status": "unpaid"
        },
        {
            "id": 2,
            "name": "Uang Gedung",
            "due_date": "2020-03-21",
            "amount": 2000000,
            "status": "paid"
        },
        {
            "id": 3,
            "name": "Pendaftaran Sekolah",
            "due_date": "2020-03-21",
            "amount": 2000000,
            "status": "paid"
        },
        {
            "id": 5,
            "name": "UKS",
            "due_date": "2020-03-21",
            "amount": 2000000,
            "status": "paid"
        }
    ],
    "meta": {
        "limit": 0,
        "offset": 0,
        "total": 4
    }
}
```
> Example response (422):

```json
{
    "message": "Error message"
}
```

### HTTP Request
`GET api/bill/{category_id}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `categoryId` |  optional  | id of bill's category
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `limit` |  optional  | limit data to be queried.
    `offset` |  optional  | offset data to be queried.

<!-- END_56f3aa8fdde5a1d1308cc5c1ee8fdf24 -->

<!-- START_06873d08b6f62d151557cdac939add67 -->
## [GET] Retrieve Bill Detail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Retrieve user's bill detail

> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/bill/detail/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/bill/detail/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "id": 1,
        "name": "Uang Bulanan",
        "due_date": "2020-03-21",
        "amount": 500000,
        "status": "unpaid"
    }
}
```
> Example response (404):

```json
{
    "message": "Not Found"
}
```

### HTTP Request
`GET api/bill/detail/{bill_user_id}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `billId` |  optional  | id of bill wanna be shown

<!-- END_06873d08b6f62d151557cdac939add67 -->

#general


<!-- START_64f5d9d0590c33d90ae0168ee5aa703d -->
## api/images/{file?}
> Example request:

```bash
curl -X GET \
    -G "http://sanmaru.test/api/images/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://sanmaru.test/api/images/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthorized"
}
```

### HTTP Request
`GET api/images/{file?}`


<!-- END_64f5d9d0590c33d90ae0168ee5aa703d -->


