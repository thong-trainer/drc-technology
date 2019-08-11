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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::group(['prefix' => 'auth' ], function () {
	Route::group(['prefix' => 'user', 'as' => 'user.' ], function () {

	    Route::get('/create', 'UserController@create')->name('create');
	    // Route::get('/{id}/show', 'UserController@show')->name('show');
	    Route::get('/{id}/edit', 'UserController@edit')->name('edit');
	    Route::post('/', 'UserController@store')->name('store');
	    Route::put('/{id}', 'UserController@update')->name('update');
	    Route::delete('/{id}', 'UserController@destroy')->name('delete');
	    Route::resource('/', 'UserController');        
	});  

	Route::group(['prefix' => 'role', 'as' => 'role.' ], function () {
	    Route::get('/create', 'RoleController@create')->name('create');
	    Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
	    Route::post('/', 'RoleController@store')->name('store');
	    Route::put('/{id}', 'RoleController@update')->name('update');
	    Route::delete('/{id}', 'RoleController@destroy')->name('delete');
	    Route::resource('/', 'RoleController');        
	});  

	Route::group(['prefix' => 'setting', 'as' => 'setting.' ], function () {
	    // Route::get('/create', 'RoleController@create')->name('create');
	    // Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
	    // Route::post('/', 'RoleController@store')->name('store');
	    Route::put('/{id}', 'SettingController@update')->name('update');
	    // Route::delete('/{id}', 'RoleController@destroy')->name('delete');
	    Route::resource('/', 'SettingController');        
	});  	
});

Route::group(['prefix' => 'setup' ], function () {
	Route::group(['prefix' => 'unit', 'as' => 'unit.' ], function () {
	    Route::get('/create', 'UnitController@create')->name('create');
	    Route::get('/{id}/edit', 'UnitController@edit')->name('edit');
	    Route::post('/', 'UnitController@store')->name('store');
	    Route::put('/{id}', 'UnitController@update')->name('update');
	    Route::delete('/{id}', 'UnitController@destroy')->name('delete');
	    Route::resource('/', 'UnitController');        
	});  

	Route::group(['prefix' => 'category', 'as' => 'category.' ], function () {
	    Route::get('/create', 'CategoryController@create')->name('create');
	    Route::get('/{id}/edit', 'CategoryController@edit')->name('edit');
	    Route::post('/', 'CategoryController@store')->name('store');
	    Route::put('/{id}', 'CategoryController@update')->name('update');
	    Route::delete('/{id}', 'CategoryController@destroy')->name('delete');
	    Route::resource('/', 'CategoryController');        
	});  

	Route::group(['prefix' => 'dimension', 'as' => 'dimension.' ], function () {
	    Route::get('/create', 'DimensionController@create')->name('create');
	    Route::get('/{id}/edit', 'DimensionController@edit')->name('edit');
	    Route::post('/', 'DimensionController@store')->name('store');
	    Route::put('/{id}', 'DimensionController@update')->name('update');
	    Route::delete('/{id}', 'DimensionController@destroy')->name('delete');
	    Route::resource('/', 'DimensionController');        
	});  	

	Route::group(['prefix' => 'dimension-group', 'as' => 'dimension-group.' ], function () {
	    Route::get('/create', 'DimensionGroupController@create')->name('create');
	    Route::get('/{id}/edit', 'DimensionGroupController@edit')->name('edit');
	    Route::post('/', 'DimensionGroupController@store')->name('store');
	    Route::put('/{id}', 'DimensionGroupController@update')->name('update');
	    Route::delete('/{id}', 'DimensionGroupController@destroy')->name('delete');
	    Route::resource('/', 'DimensionGroupController');        
	});  		
	
	Route::group(['prefix' => 'customer-group', 'as' => 'customer-group.' ], function () {
	    Route::get('/create', 'CustomerGroupController@create')->name('create');
		Route::get('/{id}/show', 'CustomerGroupController@show')->name('show');    
	    Route::get('/{id}/edit', 'CustomerGroupController@edit')->name('edit');
	    Route::post('/', 'CustomerGroupController@store')->name('store');
	    Route::put('/{id}', 'CustomerGroupController@update')->name('update');
	    Route::delete('/{id}', 'CustomerGroupController@destroy')->name('delete');
	    Route::resource('/', 'CustomerGroupController');        
	});  

});

Route::group(['prefix' => 'customer', 'as' => 'customer.' ], function () {
    Route::get('/create', 'CustomerController@create')->name('create');
    Route::get('/{id}/edit', 'CustomerController@edit')->name('edit');
    Route::post('/', 'CustomerController@store')->name('store');
    Route::put('/{id}', 'CustomerController@update')->name('update');
    Route::delete('/{id}', 'CustomerController@destroy')->name('delete');
    Route::resource('/', 'CustomerController');        
});  

Route::group(['prefix' => 'supplier', 'as' => 'supplier.' ], function () {
    Route::get('/create', 'SupplierController@create')->name('create');
    Route::get('/{id}/edit', 'SupplierController@edit')->name('edit');
    Route::post('/', 'SupplierController@store')->name('store');
    Route::put('/{id}', 'SupplierController@update')->name('update');
    Route::delete('/{id}', 'SupplierController@destroy')->name('delete');
    Route::resource('/', 'SupplierController');        
});  

Route::group(['prefix' => 'company', 'as' => 'company.' ], function () {
	Route::get('/{id}/show', 'CompanyController@show')->name('show');
    Route::get('/create', 'CompanyController@create')->name('create');
    Route::get('/{id}/edit', 'CompanyController@edit')->name('edit');
    Route::post('/', 'CompanyController@store')->name('store');
    Route::put('/{id}', 'CompanyController@update')->name('update');
    Route::delete('/{id}', 'CompanyController@destroy')->name('delete');
    Route::resource('/', 'CompanyController');        
});  

Route::group(['prefix' => 'product', 'as' => 'product.' ], function () {
    Route::get('/{id}/show', 'ProductController@show')->name('show');	
    Route::get('/create', 'ProductController@create')->name('create');
    Route::get('/{id}/edit', 'ProductController@edit')->name('edit');
    Route::post('/', 'ProductController@store')->name('store');
    Route::put('/release/{id}', 'ProductController@release')->name('release');
    Route::put('/deactivate/{id}', 'ProductController@deactivate')->name('deactivate');
    Route::put('/{id}', 'ProductController@update')->name('update');
    Route::delete('/{id}', 'ProductController@destroy')->name('delete');
    Route::resource('/', 'ProductController');        
}); 

Route::group(['prefix' => 'variant', 'as' => 'variant.' ], function () {
	Route::get('/extra-price/{product_id}', 'ProductVariantController@extraPrices')->name('extra-price.list');
	Route::put('price/{product_id}', 'ProductVariantController@update')->name('update');
    Route::put('product/{product_id}', 'ProductVariantController@updateVariants')->name('list.update');
});  

Route::group(['prefix' => 'product-price', 'as' => 'product-price.' ], function () {
    // Route::get('/create', 'ProductPriceController@create')->name('create');
    // Route::get('/{id}/edit', 'ProductPriceController@edit')->name('edit');
    Route::post('/', 'ProductPriceController@store')->name('store');
    Route::put('/{id}', 'ProductPriceController@update')->name('update');
    Route::delete('/{id}', 'ProductPriceController@destroy')->name('delete');
    // Route::resource('/', 'ProductPriceController');     
});  

Route::group(['prefix' => 'stock', 'as' => 'stock.' ], function () {
    Route::get('/{id}/show', 'StockController@show')->name('show');	
    Route::get('/create', 'StockController@create')->name('create');
    Route::get('/{id}/edit', 'StockController@edit')->name('edit');
    Route::post('/', 'StockController@store')->name('store');
    Route::put('/{id}', 'StockController@update')->name('update');
    Route::put('/done/{id}', 'StockController@done')->name('done');    
    Route::delete('/{id}', 'StockController@destroy')->name('delete');
    Route::resource('/', 'StockController');        
}); 

Route::group(['prefix' => 'quotation', 'as' => 'quotation.' ], function () {
	Route::get('/{id}/print', 'QuotationController@print')->name('print');	
    Route::get('/{id}/show', 'QuotationController@show')->name('show');	
    Route::get('/create', 'QuotationController@create')->name('create');
    Route::get('/{id}/edit', 'QuotationController@edit')->name('edit');
    Route::post('/', 'QuotationController@store')->name('store');
    Route::put('/{id}', 'QuotationController@update')->name('update');
    Route::put('/confirm/{id}', 'QuotationController@confirm')->name('confirm');
    Route::delete('/{id}', 'QuotationController@destroy')->name('delete');
    Route::resource('/', 'QuotationController');        
}); 

Route::group(['prefix' => 'invoice', 'as' => 'invoice.' ], function () {
	Route::get('/{id}/print', 'InvoiceController@print')->name('print');	
    Route::get('/{id}/show', 'InvoiceController@show')->name('show');
    Route::get('/create', 'InvoiceController@create')->name('create');
    Route::get('/{id}/edit', 'InvoiceController@edit')->name('edit');
    Route::post('/', 'InvoiceController@store')->name('store');
    Route::put('/{id}', 'InvoiceController@update')->name('update');
    Route::delete('/{id}', 'InvoiceController@destroy')->name('delete');
    Route::resource('/', 'InvoiceController');        
});

Route::group(['prefix' => 'purchase', 'as' => 'purchase.' ], function () {
    Route::get('/{id}/show', 'PurchaseController@show')->name('show');	
    Route::get('/create', 'PurchaseController@create')->name('create');
    Route::get('/{id}/edit', 'PurchaseController@edit')->name('edit');
    Route::post('/', 'PurchaseController@store')->name('store');
    Route::put('/{id}', 'PurchaseController@update')->name('update');
    Route::delete('/{id}', 'PurchaseController@destroy')->name('delete');
    Route::resource('/', 'PurchaseController');        
}); 

Route::group(['prefix' => 'ajax', 'as' => 'ajax.' ], function () {
	Route::get('variant/{product_id}/{dimension_group_id}/list', 'ProductVariantController@list')->name('variant.list');
	Route::get('variant/prices/{product_id}/{currency_id}/{customer_group_id}', 'ProductVariantController@variantsByProductId')->name('variant.prices');
	Route::get('product-price/create/{product_id}', 'ProductPriceController@createAjax')->name('price.create');
	Route::get('product-price/edit/{id}', 'ProductPriceController@editAjax')->name('price.edit');
	Route::get('product-price/{customer_group_id}/{product_id}/{qty}/{variant_ids}/{currency_id}', 'ProductPriceController@priceAjax')->name('product.price.get');

	Route::get('product-price/generate/{customer_group_id}/{product_id}/{qty}/{variant_ids}/{currency_id}/{discount}', 'ProductPriceController@generateRecords')->name('product.generate.record');

	Route::put('/change-currency/{currency_id}', 'ProductPriceController@changeCurrency')->name('change.currency');

	Route::get('stock/{product_id}/{qty}/row', 'StockController@generateRow')->name('stock.row');

	// Route::get('product-price/row/{id}', 'ProductPriceController@editAjax')->name('price.edit');

    // Route::get('/create', 'ProductController@create')->name('create');
    // Route::get('/{id}/edit', 'ProductController@edit')->name('edit');
    // Route::post('/', 'ProductController@store')->name('store');
    // Route::put('/{id}', 'ProductController@update')->name('update');
    // Route::delete('/{id}', 'ProductController@destroy')->name('delete');
    // Route::resource('/', 'ProductController');        
});         
Route::get('pdfview', 'QuotationController@pdfview')->name('pdfview');