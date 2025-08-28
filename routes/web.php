<?php
/*
--------------------------------------------------------------------------
//* CRUD & Eloquent Relationship Routes
//* --------------------------------------------------------------------------
//*  This file contains route definitions for demonstrating CRUD operations
//*  and various Eloquent relationships in Laravel, including://* | - Basic CRUD for User model
//*  - One-to-One, One-to-Many, Many-to-Many, and Polymorphic relationships
//*  Each section provides examples for creating, reading, updating, and deleting
//*  related data using Eloquent ORM
--------------------------------------------------------------------------

*/

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;
use App\Models\Post;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Photo;

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



        //? Many To Many Relationship


            //* -- CREATE DATA (MANY-TO-MANY)

            Route::get('manytomany/create/{id}',function($id){
                $user = User::find($id);
                $role = new Role(['name'=>'Author']);
                $user->roles()->save($role);
            });

            //* -- READ DATA (MANY-TO-MANY)

            Route::get('manytomany/read/{id}/{role_id}',function($id,$role_id){
                $user = User::find($id);

                ///Return only first data
                // echo $user->roles()->first()->id.' = '.$user->roles()->first()->name;


                ///Return all data linked to users table id
                foreach( $user->roles as $post){
                    echo $post->id->first().' = '.$post->name.'<br>';
                }

                ///Return Specific data linked to users table id
                // echo '<pre>';
                // echo $user->roles()->where('role_id',$role_id)->first();
            });


            //* -- UPDATE DATA (MANY-TO-MANY)

            Route::get('manytomany/update/{id}/{role_id}/{role_value}',function($id,$role_id,$role_value){
                $user = User::findOrFail($id);
                if($user->roles()->where('role_id',$role_id)->update(['name'=>$role_value])){
                    return 'Updated';
                }
                else{
                    return 'enter real values';
                }
            });


            //* -- DELETE  DATA (MANY-TO-MANY)

            Route::get('manytomany/delete/{id}',function($id){

                /// DELETE ALL
                 $user = User::findOrFail($id);
                 //$user->roles()->delete();

                ///DELETE SPECIFIC
                $user->roles()->where('role_id',4)->delete();

            });


            //* ATTACH DATA (MANY-TO-MANY)

            Route::get('manytomany/attach/{id}/{role_attach_id}',function($id,$role_attach_id){
                 
                $user = User::findOrFail($id);
                $user->roles()->attach($role_attach_id);
            });


            //* DETACH DATA (MANY-TO-MANY)

            Route::get('manytomany/detach/{id}/{role_detach_id}',function($id,$role_detach_id){
                 
                $user = User::findOrFail($id);
                $user->roles()->detach($role_detach_id);
            });


            //* SYNC DATA (MANY-TO-MANY)

            Route::get('manytomany/sync',function(){
                 
                $user = User::findOrFail(1);
                $user->roles()->sync([5,6,7]);
            });



        //? Polymorphic Relationship

            //* -- CREATE DATA (Polymorphic)

            Route::get('polymorphic/createstaff/{id}/{path}',function($id,$path){
                $staff = Staff::findOrFail($id);
                $staff->photos()->create(['path'=>$path]);

            });
            Route::get('polymorphic/createprod/{id}/{path}',function($id,$path){
                $staff = Product::findOrFail($id);
                $staff->photos()->create(['path'=>$path]);

            });


            //* -- READ DATA (Polymorphic)

            Route::get('polymorphic/readstaff/{id}',function($id){
                $staff = Staff::findOrFail($id);
                //? return $staff->photos;

                /// OR 

                foreach($staff->photos as $photo){
                    echo $photo->path.' <br>';
                }

            });

            Route::get('polymorphic/readprod/{id}',function($id){
                $staff = Product::findOrFail($id);
                //? return $staff->photos;

                /// OR 

                foreach($staff->photos as $photo){
                    echo $photo->path.' <br>';
                }

            });


             //* -- UPDATE DATA (Polymorphic)

             Route::get('polymorphic/update/{id}/{image_id}',function($id,$image_id){
                    $user = Staff::findOrFail($id);
                    $update = $user->photos()->where('id',$image_id)->first()->update(['path'=>'new example']);
                    
             });

                    /// SAME METHOD TO UPDATE WITH PRODUCTS TABLE AS ABOVE



             //* -- DELETE DATA (Polymorphic)

             Route::get('polymorphic/delete/{id}/{dlt_id}',function($id,$dlt_id){
                $user = Staff::findOrFail($id);
                $delete = $user->photos()->where('id',$dlt_id)->delete();
             });
                   /// SAME METHID TO DELETE LIKE ABOVE



