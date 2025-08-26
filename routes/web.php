<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;

Route::get('/', function () {
    return view('welcome');
});

/// CRUP OPERATIONS : 
        //? One - To - One Relation 

Route::get('onetoone/insert', function () {
    $user  = User::findOrFail(1);
    $address = new Address(['name'=>'Mumbai,Maharashtra-422110']); 
    $user->address()->save($address);

});
