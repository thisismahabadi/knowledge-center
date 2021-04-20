# Live demo

The deployed version of this repo is available on:

``https://trengo.thisismahabadi.com``

# Documentation

You can use the ``knowledge-center.postman_collection.json`` file located in the root folder of project to see the documentation. As it seems you can also import the file in your ``Postman``.

# Installation

To start using the app at first put your database setting in ``.env`` file after copying ``.env.example`` to ``.env``.

After that try to install dependencies using:

``composer install``

The next thing you should do is set your application key to a random string by:

``php artisan key:generate``

And then you should migrate and seed data.

To migrate and seed at same time:

``php artisan migrate --seed``

And to migrate and seed separately:

``php artisan migrate``

``php artisan db:seed``

Note that it will takes a long time if you want to seed by console, you can just run the project home page, and it will seed database by dispatching a job in ``Redis Queue`` in background.

Before run the home page remember to change ``QUEUE_CONNECTION`` to redis in .env file.

``{{url}}/``

Remember to run your queue by:

``php artisan queue:listen --timeout=0``

Allright, now you can serve the project:

``php artisan serve``

# Testing

The tests will run on ``sqlite``.

Make sure that you have installed php's sqlite extension.

Feature tests are available for all routes, therefore you can test the project like this:

``vendor/bin/phpunit``

Also there's a unit test available for the weighted ranking unit.

# Notes

Below data will seed, keep in mind if you wanted to seed these amount of data it may takes time.

| Title  | Seeded record number |
| ------------- |:-------------:|
| Categories      | 10     |
| Articles      | 1,000     |
| Ratings      | 10,000     |
| Views      | 100,000     |

There are ``rate limitings`` available on the routes and an user just can send requests 10,000,000 times per minute (as you know better, it can be any number).

``Laravel Form Request Validation`` method used to validate the data which user sends.

Keep in mind to send ``Accept: application/json`` on header for all requests as the examples on Postman's documentation.

A user can rate to articles with integer score numbers between 1 to 5.

A ``view log`` will register via every show request on articles. It means every time user request to visit an article a ``new view`` will recorded. but it limited by ``IP Address``.

The ``view log`` strategy is implemented with ``Laravel Queue`` so remember to run queue to register.

``php artisan queue:listen --timeout=0``

A user (an IP address) may only rate once to an article and only 10 times total in the last day.

Also note that the articles with zero views will not be listed when you try to get list of articles and sort them by views at the same time, but they will be showed if user doesn't specify sorting based on view.

``API routes`` are available in postman collection but you can seem them here also:

- Show the list of articles - [GET]

    ``{{url}}/articles``

        'categories' => 'array'
        'date' => 'array'
        'sort' => 'array'
        'limit' => 'integer'
        'search' => 'string'

- Create a new article - [POST]

    ``{{url}}/articles``

        'title' => 'required|string'
        'body' => 'required|string'
        'categories' => 'array'

- Rate an article - [POST]

    ``{{url}}/ratings``

        'article_id' => 'integer'
        'score' => 'required|integer|between:1,5'

- Show detail of an specific article - [GET]

    ``{{url}}/articles/{articleId}``

# Todo

- [x] Make controller and model methods lighter.
- [ ] Make services lighter.
- [ ] Implement full unit tests.
- [ ] Improve feature tests.
- [ ] Improve query performances.
- [ ] Imrpove error handling.
- [ ] Dockerizing.
- [ ] Implement Horizon package.
- [ ] Implement fuzzy search.
