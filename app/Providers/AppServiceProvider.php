<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Setting;
use App\Widget;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    $setting = Setting::firstOrFail();
    View::share ('setting', $setting);

    $header_contact_info = Widget::where('title','=','header-contact-info')->first(['content']);
    View::share ('header_contact_info', $header_contact_info);

    $footer_contact_info = Widget::where('title','=','footer-contact-info')->first(['content']);
    View::share ('footer_contact_info', $footer_contact_info);

    $footer_map = Widget::where('title','=','footer-map')->first(['content']);
    View::share ('footer_map', $footer_map);

    $footer_widget_area = Widget::where('title','=','footer-widget-area')->first(['content']);
    View::share ('footer_widget_area', $footer_widget_area);

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
