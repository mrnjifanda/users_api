<?php
namespace App;

use App\Http\Request;
use App\Helpers\Sessions;
use App\Helpers\Cookies;
use App\Users\Auth;

class Router
{
    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;

    /**
     * @var App
     */
    private $app;

    public function __construct (string $viewPath, App $app)
    {
        $this->app = $app;
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
    }

    public function get (string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', '/' . $url, 'controllers/get/' . $view, $name);
        return $this;
    }

    public function post (string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', '/' . $url, 'controllers/post/' . $view, $name);
        return $this;
    }

    public function match (string $url, string $view, ?string $type = null, ?string $name = null): self
    {
        $this->router->map('POST|GET', '/' . $url, ($type ? $this->core : $this->view) . $view, $name);
        return $this;
    }

    public function url (string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function run (): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        $router = $this;
        $app = $this->app;
        $request = new Request($_GET);

        if (is_array($match)) {

            if (is_callable($view)) {
                call_user_func_array($view, $match['params']);
            } else {
                $params = $match['params'];
                ob_start();
                require $this->viewPath.$view.'.php';
                $pageContent = ob_get_clean();
            }
            require $this->viewPath.'controllers/default.php';
        } else {
            require $this->viewPath.'controllers/404.php';
        }
        return $this;
    }
}