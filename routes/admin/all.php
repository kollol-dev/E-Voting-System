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

Route::get('/user/get/non-candidate', 'Admin\ElectionController@getNonCandidate');
Route::get('/user/get/alumni', 'Admin\ElectionController@getAlumni');

// Election Routes
Route::get('/election/get/all', 'Admin\ElectionController@getAllElection');
Route::get('/election/paginate/all', 'Admin\ElectionController@paginateElection');

Route::post('/election/add/new', 'Admin\ElectionController@addNewElection');
Route::post('/election/update/{id}', 'Admin\ElectionController@updateElection');
Route::post('/election/delete/{id}', 'Admin\ElectionController@deleteElection');



// election posts
Route::get('/election/posts/get/all', 'Admin\ElectionController@getAllElectionPosts');
Route::get('/election/posts/paginate/all', 'Admin\ElectionController@paginateElectionPosts');
Route::post('/election/posts/add/new', 'Admin\ElectionController@addNewElectionPost');
Route::post('/election/posts/update/{id}', 'Admin\ElectionController@updateElectionPost');
Route::post('/election/posts/delete/{id}', 'Admin\ElectionController@deleteElectionPost');
Route::get('/election/posts/election/{id}', 'Admin\ElectionController@getPostsByElectionId');




// election posts candidates
Route::get('/election/candidate/paginate/all', 'Admin\ElectionController@paginateElectionCandidates');
Route::post('/election/candidate/add/new', 'Admin\ElectionController@addNewElectionCandidate');
Route::post('/election/candidate/update/{id}', 'Admin\ElectionController@updateElectionCandidate');
Route::post('/election/candidate/delete/{id}', 'Admin\ElectionController@deleteElectionCandidate');
Route::get('/election/cadidate/check/user/{id}', 'Admin\ElectionController@checkUsersForNomination');