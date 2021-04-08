# Documentation
You can use the ``knowledge-center.postman_collection.json`` file located in the root folder of project to see the documentation. As it seems you can also import the file in your ``Postman``.

# Installation
To start using the app at first put your database setting in ``.env`` file and then you should migrate and seed data.

Migrate and seed at same time:

``php artisan migrate --seed``

Migrate and seed separately:

``php artisan migrate``

``php artisan db:seed``

Allright, now you can serve the project:

``php artisan serve``

# Notes

Below data will seed, keep in mind if you wanted to seed these amount of data it may takes time.

| Title  | Seeded record number |
| ------------- |:-------------:|
| Categories      | 10     |
| Articles      | 1,000     |
| Ratings      | 10,000     |
| Views      | 100,000     |

There are ``rate limitings`` available on the routes and an user just can send requests 100 times per minute.

``Laravel Form Request Validation`` method used to validate the data which user sends.

Keep in mind to send ``Accept: application/json`` on header for all requests as the examples on Postman's documentation.

A user can rate to articles with integer score numbers between 1 to 5.

A ``view log`` will register via every show request on articles. It means every time user request to visit an article a ``new view`` will recorded. but it limited by ``IP Address``.

A user (an IP address) may only rate once to an article and only 10 times total in the last day.

``API routes`` are available in postman collection but you can seem them here also:

- Show the list of articles - [GET]

    ``{{url}}/articles``

        'categories' => 'array'
        'date' => 'array'
        'sort' => 'string'
        'view_date' => 'date'
        'limit' => 'integer'
        'search' => 'string'

- Create a new article - [POST]

    ``{{url}}/articles``

        'title' => 'required|string'
        'body' => 'required|string'
        'categories' => 'array'

- Rate an article - [POST]

    ``{{url}}/articles/{articleId}/rate``

        'score' => 'required|integer|between:1,5'

- Show detail of an specific article - [GET]

    ``{{url}}/articles/{articleId}``