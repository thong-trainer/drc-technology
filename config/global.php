<?php

return [

    'page_limit' => 10,
    'default_password' => '123456',
    'codes' => [
        'customer' => 'CC',
        'supplier' => 'iS',
        'product' => 'iP',
        'quotation' => 'SO',
        'invoice' => 'INV',
        'stock_in' => 'IN',
        'stock_out' => 'OUT',
    ],

    'payment_methods' => [
        'cash' => 'Cash',
        'bank' => 'Bank',
    ],    

    'modules' => [
        'user' => 'user',
        'role' => 'role',
        'customer' => 'customer',
        'customer_group' => 'customer_group',
        'category' => 'category',
        'supplier' => 'supplier',
        'company' => 'company',
        'dimension' => 'dimension',
        'product' => 'product',
        'product_price' => 'product_price',
        'stock' => 'stock',
        'sale' => 'sale',
        'quotation' => 'quotation',
        'invoice' => 'invoice',
        'setting' => 'setting',
    ],
    
    'paths' => [
        'root' => '',
        'user' => '/uploads/users/',
        'category' => '/uploads/categories/',
        'contact' => '/uploads/contacts/',
        'product' => '/uploads/products/',
    ],
    
    'stock_status' => [
        'stock_in' => 'stock_in',
        'stock_out' => 'stock_out',
    ],

    'stock_movement_status' => [
        'waiting' => 'Waiting',
        'done' => 'Done',
    ],

    'quotation_status' => [
        'pending' => 'Quotation',
        'confirmed' => 'Sale Order',
        'invoiced' => 'Invoiced',
        'deleted' => 'Deleted',
    ],

    'invoice_status' => [
        'issued' => 'Issued',
        'paid' => 'Paid',
        'deleted' => 'Deleted',
    ],


];
