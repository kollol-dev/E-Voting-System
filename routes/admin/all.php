<?php

// Route::group(
//     [
//         'as' => 'admin.',
//         'namespace' => 'Admin',
//         'prefix' => '/app/admin',
//     ],
//     function () {


//     }
// );



//     prefix = /app/admin 
Route::get('/get/users/all', 'Admin\AdminController@getAllUsers');
Route::get('/paginate/users/all', 'Admin\AdminController@paginateAllUsers');

Route::post('/update/user/{id}', 'Admin\AdminController@editUser');
Route::post('/delete/user/{id}', 'Admin\AdminController@deleteUser');



// Election Routes
Route::get('/election/get/all', 'Admin\ElectionController@getAllElection');
Route::get('/election/paginate/all', 'Admin\ElectionController@paginateElection');

Route::post('/election/add/new', 'Admin\ElectionController@addNewElection');
Route::post('/election/update/{id}', 'Admin\ElectionController@updateElection');
Route::post('/election/delete/{id}', 'Admin\ElectionController@deleteElection');
