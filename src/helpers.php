<?php

use App\Helpers\General\Timezone;
use App\Helpers\General\HtmlHelper;
use App\Models\Cart;

if(! function_exists('get_upload_directory')){
    function get_upload_directory(){
        $directories = Storage::disk('public')->directories('images');
        foreach($directories as $directory){
            if(sizeof(Storage::disk('public')->files($directory)) < config('filesystems.max_image_count')){
                return $directory.'/';
            }
        }
        return 'images/'.str_pad(sizeof($directories),20,"0",STR_PAD_LEFT).'/';
    }
}

if(! function_exists('get_cart')) {
    /**
     * Helper to get the cart from either the session or by user name
     *
     * @return mixed
     */
    function get_cart(){
        if(auth()->check()){
            //use auth()->id
            $cart = Cart::where('user_id', auth()->id())->where('is_cart', true)->first();
            if(is_null($cart)){
                $cart = new Cart([
                    'title' => "Cart",
                    'description' => "Default Cart",
                    'user_id' => auth()->id(),
                    'is_cart' => true,
                    'is_active' => true,
                ]);
                $cart->save();
            }
        }else{
            //use auth()->id
            $cart = Cart::where('session_id', session()->getId())->where('is_cart', true)->first();
            if(is_null($cart)){
                $cart = new Cart([
                    'title' => "Cart",
                    'description' => "Default Cart",
                    'session_id' => session()->getId(),
                    'is_cart' => true,
                    'is_active' => true,
                ]);
                $cart->save();
            }
        }
        return $cart;
    }
}

/*
 * Global helpers file with misc functions.
 */
if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}

if (! function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (! function_exists('home_route')) {

    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            } else {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if (! function_exists('style')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}

if (! function_exists('form_cancel')) {

    /**
     * @param        $cancel_to
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_cancel($cancel_to, $title, $classes = 'btn btn-danger btn-sm')
    {
        return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
    }
}

if (! function_exists('form_submit')) {

    /**
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_submit($title, $classes = 'btn btn-primary btn-sm')
    {
        return resolve(HtmlHelper::class)->formSubmit($title, $classes);
    }
}

if (! function_exists('camelcase_to_word')) {

    /**
     * @param $str
     *
     * @return string
     */
    function camelcase_to_word($str)
    {
        return implode(' ', preg_split('/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x', $str));
    }
}
