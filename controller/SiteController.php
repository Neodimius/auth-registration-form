<?php

use model\User;

class SiteController
{
    /**
     * @var array|string[]
     */
    public static array $routes = ['index', 'registration', 'login', 'logout'];

    /**
     * @param string $route
     */
    public function __construct(string $route)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (in_array($route, self::$routes, true)) {
            $this->{$route}();
        } else {
            echo 'wrong request';
            die;
        }
    }

    /**
     * @param array $message
     * @return void
     */
    public function index(array $message = []): void
    {
        $user = new User();
        $isAuth = $user->isAuthorized();

        require_once 'view/index.php';
    }

    /**
     * @return void
     * @throws Exception
     */
    public function registration(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && hash_equals($_SESSION['csrfToken'], $_POST['csrfToken'])) {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $password = $_POST["password"];
            $user = new User();

            if ($user->validate($_POST)) {
                if ($user->registerUser($firstName, $lastName, $email, $phone, $password)) {

                    $this->index([
                        'type' => 'success',
                        'text' => 'Registration successful'
                    ]);
                } else {
                    $this->index([
                        'type' => 'danger',
                        'text' => 'Registration failed.'
                    ]);
                }
                exit;
            }
        }

        $csrfToken = $_SESSION['csrfToken'] = bin2hex(random_bytes(24));

        require_once 'view/registration.php';
    }

    /**
     * @return void
     * @throws Exception
     */
    public function login(): void
    {
        if (isset($_POST["email"], $_POST["password"]) && hash_equals($_SESSION['csrfToken'], $_POST['csrfToken'])) {
            $user = new User();
            $email = $_POST["email"];
            $password = $_POST["password"];

            if ($user->authorizeUser($email, $password)) {
                $this->index([
                    'type' => 'success',
                    'text' => 'Authorization successful'
                ]);

                exit();
            }

            $message = [
                'type' => 'danger',
                'text' => 'Authorization failed.  Please check email and password'
            ];
        }

        $csrfToken = $_SESSION['csrfToken'] = bin2hex(random_bytes(24));

        require_once 'view/login.php';
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $user = new User();
        $user->logout();

        $this->index();
    }
}