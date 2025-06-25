<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::pattern('id','[0-9]+');

/********************  Backend routes  *************************/

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'prevent-back-history' ], function() {

    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('login.submit');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('logout');
    #Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    #Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestFrom')->name('password.request');
    #Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
    #Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('password.reset');

    Route::get('/', 'AdminController@index')->name('dashboard');

    Route::get('/users', 'UserController@index')->name('users.list');
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/create', 'UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::patch('/user/update/{id}', 'UserController@update')->name('user.update');
    Route::get('/user/change_password/{id}', 'UserController@showPasswordFrom')->name('user.change_password');
    Route::patch('/user/change_password/{id}', 'UserController@changePassword')->name('user.password.create');
    Route::get('/user/show/{id}', 'UserController@show')->name('user.show');
    Route::post('/user/changestatus', 'UserController@changeStatus')->name('user.change_status');
    Route::get('/user/delete/{id}', 'UserController@destroy')->name('user.delete');
    Route::post('/users/delete', 'UserController@multiple_user_delete')->name('users.delete');
    Route::get('/users/truncate', 'UserController@truncate')->name('users.truncate');

    Route::match(['get','patch'],'/settings', 'SettingController@update')->name('site.setting');
    Route::match(['get','patch'],'/profile', 'AdminController@profile')->name('profile');
    Route::match(['get','patch'],'/change_password', 'AdminController@change_password')->name('change_password');
    Route::match(['get','post','patch'],'/widgets', 'WidgetController@manage')->name('widgets');
    Route::delete('/widget/delete/{id}', 'WidgetController@destroy')->name('widget.delete');
    Route::get('/widgets/truncate', 'WidgetController@truncate')->name('widgets.truncate');
    Route::match(['get','post'],'/seo-setting', 'SeoSettingController@manage')->name('seo.setting');

    Route::get('/categories', 'CategoryController@index')->name('categories.list');
    Route::get('/category/create', 'CategoryController@create')->name('category.create');
    Route::post('/category/create', 'CategoryController@store')->name('category.store');
    Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category.edit');
    Route::put('/category/slug/update', 'CategoryController@slug')->name('category.update.slug');
    Route::patch('/category/update/{id}', 'CategoryController@update')->name('category.update');
    Route::get('/category/show/{id}', 'CategoryController@show')->name('category.show');
    Route::delete('/categories/delete', 'CategoryController@multiple_categories_delete')->name('categories.delete');

    Route::get('/tags', 'TagController@index')->name('tags.list');
    Route::get('/tags/create', 'TagController@create')->name('tag.create');
    Route::post('/tags/create', 'TagController@store')->name('tag.store');
    Route::get('/tags/edit/{id}', 'TagController@edit')->name('tag.edit');
    Route::put('/tags/slug/update', 'TagController@slug')->name('tag.update.slug');
    Route::patch('/tags/update/{id}', 'TagController@update')->name('tag.update');
    Route::get('/tags/show/{id}', 'TagController@show')->name('tag.show');
    Route::get('/tag/delete/{id}', 'TagController@destroy')->name('tag.delete');
    Route::delete('/tags/delete', 'TagController@multiple_tags_delete')->name('tags.delete');
    Route::get('/tags/truncate', 'TagController@truncate')->name('tags.truncate');

    Route::get('/posts/{cat_id?}/{tag_id?}', 'PostController@index')->name('posts.list');
    Route::get('/post/create', 'PostController@create')->name('post.create');
    Route::post('/post/create', 'PostController@store')->name('post.store');
    Route::get('/post/edit/{id}', 'PostController@edit')->name('post.edit');
    Route::put('/post/slug/update', 'PostController@slug')->name('post.update.slug');
    Route::patch('/post/update/{id}', 'PostController@update')->name('post.update');
    Route::get('/post/show/{id}', 'PostController@show')->name('post.show');
    Route::get('/post/delete/{id}', 'PostController@destroy')->name('post.delete');
    Route::delete('/posts/delete', 'PostController@multiple_posts_delete')->name('posts.delete');
    Route::get('/posts/deleteall', 'PostController@deleteall')->name('posts.delete.all');
    Route::post('/post/meta/create', 'PostController@meta_create')->name('post.meta.store');
    Route::patch('/post/meta/update/{id}', 'PostController@meta_update')->name('post.meta.update');
    Route::delete('/post/meta/delete/{id}', 'PostController@meta_delete')->name('post.meta.delete');
    Route::post('/post/files/create', 'PostController@multiple_file_upload')->name('post.files.store');
    Route::get('/post/file/delete/{id}', 'PostController@file_delete')->name('post.file.delete');
    Route::patch('/post/meta-element/update/{id}', 'PostController@meta_element_update')->name('post.meta.element.update');
    Route::post('/post/changestatus', 'PostController@changeStatus')->name('post.change.status');

    Route::get('/pages', 'PageController@index')->name('pages.list');
    Route::get('/page/create', 'PageController@create')->name('page.create');
    Route::post('/page/create', 'PageController@store')->name('page.store');
    Route::get('/page/edit/{id}', 'PageController@edit')->name('page.edit');
    Route::put('/page/slug/update', 'PageController@slug')->name('page.update.slug');
    Route::put('/page/update/status', 'PageController@update_page_status')->name('page.update.status');
    Route::patch('/page/update/{id}', 'PageController@update')->name('page.update');
    Route::get('/page/show/{id}', 'PageController@show')->name('page.show');
    Route::get('/page/delete/{id}', 'PageController@destroy')->name('page.delete');
    Route::delete('/pages/delete', 'PageController@multiple_pages_delete')->name('pages.delete');
    Route::get('/pages/deleteall', 'PageController@deleteall')->name('pages.delete.all');
    Route::post('/page/meta/create', 'PageController@meta_create')->name('page.meta.store');
    Route::patch('/page/meta/update/{id}', 'PageController@meta_update')->name('page.meta.update');
    Route::delete('/page/meta/delete/{id}', 'PageController@meta_delete')->name('page.meta.delete');
    Route::patch('/page/meta-element/update/{id}', 'PageController@meta_element_update')->name('page.meta.element.update');
    Route::post('/page/changestatus', 'PageController@changeStatus')->name('page.change.status');

    Route::get('/resources', 'MediaController@index')->name('resources.list');
    Route::get('/resources/create', 'MediaController@create')->name('resources.create');
    Route::post('/resources/create', 'MediaController@store')->name('resources.store');
    Route::delete('/resources/delete', 'MediaController@multiple_file_delete')->name('resources.delete');

    Route::get('/contact-emails', 'ContactFormController@index')->name('contact.forms.list');
    Route::get('/contact-emails/show/{id}', 'ContactFormController@show')->name('contact.form.show');
    Route::get('/contact-emails/delete/{id}', 'ContactFormController@destroy')->name('contact.form.delete');

    Route::get('/subscribe-emails', 'SubscriberController@index')->name('subscribers.list');

    Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor')->name('2fa.enable');
    Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor')->name('2fa.disable');
    Route::get('/2fa/validate', 'Auth\AdminLoginController@getValidateToken')->name('2fa.validate');
    Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\AdminLoginController@postValidateToken'])->name('2fa.post.validate');

    /********************  addtional routes  *************************/

    Route::get('/segments', 'SegmentController@index')->name('segments.list');
    Route::get('/segment/create', 'SegmentController@create')->name('segment.create');
    Route::post('/segment/create', 'SegmentController@store')->name('segment.store');
    Route::get('/segment/edit/{id}', 'SegmentController@edit')->name('segment.edit');
    Route::patch('/segment/update/{id}', 'SegmentController@update')->name('segment.update');
    Route::get('/segment/delete/{id}', 'SegmentController@destroy')->name('segment.delete');

    Route::get('/packages', 'PackageController@index')->name('packages.list');
    Route::get('/package/create', 'PackageController@create')->name('package.create');
    Route::post('/package/create', 'PackageController@store')->name('package.store');
    Route::get('/package/edit/{id}', 'PackageController@edit')->name('package.edit');
    Route::patch('/package/update/{id}', 'PackageController@update')->name('package.update');
    Route::get('/package/delete/{id}', 'PackageController@destroy')->name('package.delete');

    Route::get('/slots', 'SlotController@index')->name('slots.list');
    Route::get('/slot/create', 'SlotController@create')->name('slot.create');
    Route::post('/slot/create', 'SlotController@store')->name('slot.store');
    Route::get('/slot/edit/{id}', 'SlotController@edit')->name('slot.edit');
    Route::patch('/slot/update/{id}', 'SlotController@update')->name('slot.update');
    Route::get('/slot/delete/{id}', 'SlotController@destroy')->name('slot.delete');
    Route::post('/get-slots', 'SlotController@search')->name('slots.search');

    Route::match(['get','post'],'/bookings', 'TransactionController@index')->name('transactions');
    Route::post('/cancel-tx', 'TransactionController@changeStatus')->name('tx.change.status');

  });

/********************  Frontend user routes  *************************/

Auth::routes();

Route::group(['middleware' => 'prevent-back-history'], function() {

Route::get('/home', 'HomeController@index')->name('home');
Route::get('users/logout', 'Auth\LoginController@userLogout')->name('users.logout');
Route::get('/booking-details/{slug}', 'HomeController@booking_details')->name('booking.details');
Route::post('/booking-enquiry', 'HomeController@booking_enquiry')->name('booking.enquiry.store');
Route::post('/stripe', 'StripeController@stripe')->name('booking.stripe');
Route::post('/paypal', 'HomeController@paypal')->name('booking.paypal');

Route::post('/stripe-payment', 'StripeController@stripe_payment')->name('stripe.payment');

Route::get('/paypal-success', 'HomeController@paypal_success')->name('paypal.success');
Route::get('/paypal-cancel', 'HomeController@paypal_cancel')->name('paypal.cancel');

Route::match(['get','patch'],'/change-email', 'HomeController@update_email')->name('user.email');
Route::post('/verfy-email', 'HomeController@verfy_email')->name('user.verify.email');
Route::post('/resend-otp', 'HomeController@resend')->name('user.resend.otp');
Route::match(['get','patch'],'/profile', 'HomeController@profile')->name('user.profile');
Route::match(['get','patch'],'/change-password', 'HomeController@change_password')->name('user.change_password');

Route::get('/bookings', 'HomeController@bookings')->name('transactions');
Route::get('/booked-class-details/{id}', 'HomeController@class_details')->name('booked.class.details');
Route::get('/private-booking-details/{slug}', 'HomeController@private_booking_details')->name('private.booking.details');

Route::post('/paywith-paypal', 'HomeController@paypal_custom')->name('booking.paypal.custom');
Route::post('/paywith-stripe', 'StripeController@stripe_custom')->name('booking.stripe.custom');

Route::post('/stripe-payment-custom', 'StripeController@stripe_payment_custom')->name('stripe.payment.custom');

Route::get('stripe-checkout','StripeController@stripe_checkout')->name('stripe.checkout');
Route::get('stripe-canceled','StripeController@stripe_canceled')->name('stripe.cancel');
Route::get('stripe-success','StripeController@stripe_success')->name('stripe.success');
Route::get('stripe-segment-success','StripeController@stripe_segment_success')->name('stripe.segment.success');

Route::post('/select-combo-pack', 'HomeController@select_combo_pack')->name('select.combo.pack');

});

/********************  Frontend routes  *************************/

Route::get('/', 'PageController@index')->name('site');
Route::get('verify/{email}/{verifyToken}', 'Auth\RegisterController@sendEmailDone')->name('sendEmailDone');
Route::get('404',['as'=>'404','uses'=>'ErrorHandlerController@errorCode404']);
Route::post('/booking-confirmation', 'PageController@booking_confirmation')->name('booking.confirmation');
Route::post('/booking-enquiry', 'HomeController@booking_enquiry')->name('booking.enquiry.store');
Route::post('/contact-form', 'PageController@contact_form')->name('contact.form');
Route::post('/private-class-custom', 'PageController@private_booking_confirmation')->name('private-class-custom');
Route::get('/{slug}', 'PageController@other');
Route::get('/class-details/{slug}', 'PageController@class_details')->name('class.details');
