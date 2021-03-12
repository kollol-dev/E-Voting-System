<?php
//     prefix = /app/alumni 



// election posts candidates
Route::get('/election/posts/{id}', 'Alumni\ElectionController@getPostsByELectionId');
Route::get('/election/candidate/{id}', 'Alumni\ElectionController@getCandidateByELectionId');
Route::get('/election/vote-check/{id}', 'Alumni\ElectionController@checkVoteCastByElectionId');
Route::post('/election/cast-vote/{id}', 'Alumni\ElectionController@castVote');

// Route::post('/election/candidate/add/new', 'Alumni\ElectionController@addNewElectionCandidate');
// Route::post('/election/candidate/update/{id}', 'Alumni\ElectionController@updateElectionCandidate');
// Route::post('/election/candidate/delete/{id}', 'Alumni\ElectionController@deleteElectionCandidate');
