## Getting Started

1.  Clone the repository to your local machine:

        ```sh
        git clone hhttps://github.com/abrahamemmanuel/ecommerce-kafka-assessment.git
        ```

    cd project-name

Install Composer dependencies:

    ```sh
    composer install
    ```

Copy the .env.example file to .env and update the necessary environment variables:

        ```sh
        cp .env.example .env
        ```

Generate an app encryption key:

    ```sh
    php artisan key:generate
    ```

To run tests, use the following command:

    ```sh
    php artisan test
    ```
