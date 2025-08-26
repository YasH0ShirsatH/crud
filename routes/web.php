<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;
use App\Models\Post;

Route::get('/', function () {
    return view('welcome');
});


/// first create a unction to insert into user

    Route::get('/insert', function () {
        $user  = new User([
            'name'=> 'vignesh',
            'email'=> 'vignesh@gmail.com',
            'password'=> 'yassdfsdf',
            'remember_token'=> 'yassdfasdasdsdf',
        ]);

        if($user->save()){
            return 'inserted data';
        }

    });



/// CRUD OPERATIONS : 

        //? One - To - One Relation 

            //* -- CREATE (ONE-TO-ONE)   

            Route::get('onetoone/create/{id}', function ($id) {
                $user  = User::findOrFail($id);
                $address = new Address(['name'=>'Nashik,Maharashtra-422110']); 
                $user->address()->save($address);

            });

            
            //* -- READ DATA (ONE-TO-ONE)

            Route::get('onetoone/read/{id}', function ($id) {
                    $address = User::find($id)->first();
                    echo $address->address->name;
                    

                    
                });


            //* -- UPDATE (ONE-TO-ONE)

                //! ---- UPDATE SINGULAR DATA

                Route::get('onetoone/update/{id}', function ($id) {
                    $address = Address::where('user_id',$id)->first();
                    $address->name = 'Pune';
                    if($address->save()){
                        return 'updated';
                    }
                    else{
                        return 'not updated';
                    }
                });

                //! ---- UPDATE MULTIPLE DATA

                Route::get('onetoone/update2/{id}', function ($id) {
                    $address = Address::where('name',$id)
                                        ->update(['name'=> 'MAHARASHTRA']);

                    
                });
            


            //* -- DELETE DATA (ONE-TO-ONE)

            Route::get('onetoone/delete/{id}',function($id){
                $address = User::find($id)->first();
                $address->address->delete();
            });





        //? One To Many Relation

            //* CREATE DATA (ONE-TO-MANY)

            Route::get('onetomany/insert/{id}',function($id){
                $user = User::findOrFail($id);
                $post = new Post(['title'=>'laravel','body'=>'new laravel proj']);
                $user->posts()->save($post);
            }); 


            //* READ DATA (ONE-TO-MANY)

            Route::get('onetomany/read/{id}',function($id){
                $users= User::findOrFail($id);
                foreach($users->posts as $user){
                    echo '<pre>';
                        echo $user->title .' ';
                        echo $user->body .' ';
                        echo $user->created_at .' ';
                        echo $user->updated_at .' ';
                    echo '</pre>';
                }
            }); 

            //* -- UPDATE (ONE-TO-MANY)

                //! ---- UPDATE SINGULAR DATA (ONE-TO-MANY)

                Route::get('onetomany/update/{id}', function ($id) {
                    $user = User::where('id',$id)->first();
                    $user->posts()->whereId($id)->update(['title'=>'laravel','body'=>'laravel is decent']); 
                });

                //! ---- UPDATE MULTIPLE DATA (ONE-TO-MANY)

                Route::get('onetomany/update2/{id}', function ($id) {
                    $user = User::where('id',$id)->first();
                    foreach ($user->posts as $post) {
                        $post->title = 'new ' . $post->id;
                        $post->body = 'This is a new body for Post ID: ' . $post->id;
                        $post->save();
                    }
                });
            


            //* -- DELETE METHOD (ONE-TO-MANY)

            Route::get('onetomany/delete/{id}',function($id){
                $user = User::where('id',$id)->first();

                //? to delete all records connected to main users table id
                $user->posts()->delete();
                //? to delete first record connected to main users table id
                $user->posts()->first()->delete();
            });


                //! DELETE SPECFIC DATA ATTACHED TO MAIN (users) TABLE
                Route::get('onetomany/delete/{user_id}/{post_id}',function($user_id,$post_id){
                    $user = User::where('id',$user_id)->first();

                    //? to delete specific records connected to main users table id
                    $user->posts()->where('id',$post_id)->delete();
                    
                });

                

        //? Many To Many Realtionship


            //* -- CREATE DATA (MANY-TO-MANY)