<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Contact;
use App\Models\UserContact;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Auth;

class UserController extends Controller
{
//REGISTER USER//

  public function createUser(Request $request){

    $response = "";

        //Getting data from request
    $data = $request;

    $data->validate([
      'username'=>'required',
      'password'=>'required',
      'email'=>'required'
    ]);

    if($data!=""){
            //TODO: validate user input data 
      if($data){

                //Create user
        $user = new User();


                //Required data
        $user->name_user = $data->username;
        $user->password_user = Hash::make($data->password);
        $user->email_user = $data->email;
                //Save user
        try{

          $user->save();

          $successmsg = "User registered successfully";
          return redirect('register')->with(['successmsg'=>$successmsg]);
        
        }catch(\Exception $e){
          $response = $e->getMessage();
        }

      }

    }else{
      $response = "Wrong data";
    }



    return redirect("register");
  }

//LOGIN USER//

  public function loginUser(Request $request){

    $response = "";

        //Getting data from request
    $data = $request->getContent();

        //Verify data exists
    $data = json_decode($data);

       //Get password from username
    $hashedPassword = User::where('email_user', $data->email)->value('password_user');
        //Password from response
    $responsePassword = $data->password;
        //Find what ID the user has based on his username
    $userLoggedId = User::where('email_user',$data->email)->value('id');
    $userLogged=User::find($userLoggedId);

    if($data!=""){
            //TODO: validate user input data 
      if($data){

                //Check if user and password exists in the database
        if (User::where('email_user', '=', $data->email)->exists() && Hash::check($responsePassword , $hashedPassword)) {

                    //Token from laravel passport
         $token = $userLogged->createToken('contact')->accessToken;
                   //Put laravel passport token into username token field
         $userLogged->api_token = $token;
         $userLogged->save();
         $userName = User::where('email_user', $data->email)->value('name_user');
                    //The token is needed in order to access the system
         return response()->json([

          'respuesta' => 'Logged as'." ".$userName,

          'token'=>$token,
          'id'=>$userLogged->id
        ]);     

       }else{
        return 'Username or password are incorrect';
      }

    }else{
      $response = "Wrong data input";
    }

  }
}
//LOGOUT USER//
public function logoutUser(Request $request){

  $response = "";

            //Getting data from request
  $data = $request->getContent();

            //Verify data exists
  $data = json_decode($data);


  if($data){

    $user = User::find($data->id);

    if($user){

      $user->api_token = NULL;

      try{
        $user->save();

        $response = "OK, user logged out";

      }catch(\Exception $e){
        $response = $e->getMessage();
      }
    }else{

      $response = "User doesn't exist";
    }
  }else{

    $response = "User not found";
  }
        // return response($response);
  return response($response);
}
//EDIT USER//
public function editUser(Request $request){

 $respuesta = "";

 $datos = $request->getContent();
 $datos = json_decode($datos);

 $apiToken = $request->bearerToken();
 $userID = User::where('api_token',$apiToken)->value('id');

	//Check if user exists
 $user = User::find($userID);

 if($user){

  if($datos){

                //Check if values exists on request
    if(isset($datos->email)){
     $user->email_user = $datos->email;
   }
   if(isset($datos->name)){
     $user->name_user = $datos->name;
   }


				//Save user
   try{

     $user->save();

     $respuesta = "OK";
   }catch(\Exception $e){
     $respuesta = $e->getMessage();
   }
 }else{
  $respuesta = "Incorrect data";
}
}else{
  $response = "User not found";
}

return $user;
}

//DELETE USER//
public function deleteUser(Request $request){

  $respuesta = "";
  $apiToken = $request->bearerToken();
  $userID = User::where('api_token',$apiToken)->value('id');
  $user = User::find($userID);

  if($user){

        //Delete user
    try{

      $user->delete();

      $respuesta = "User deleted";
    }catch(\Exception $e){
      $respuesta = $e->getMessage();
    }

  }else{
    $response = "User not found";
  }


  return response($respuesta);
}

//SET CONTACT LIST FROM EACH USER//
public function showContactList(Request $request){

  $datos = $request->getContent();
  $datos = json_decode($datos);

  $apiToken = $request->bearerToken();
  $userID = User::where('api_token',$apiToken)->value('id');

    //$userContacts = UserContact::where('user_id',$userID)->get()->toArray();
    //$venta = Venta::orderByRaw('price','ASC')->get();
  $users = User::where('id',$userID)->get()->toArray();
  $contacts = Contact::all();
  $userContacts = UserContact::all();
  $list = [];
  foreach($users as $user){

    foreach($userContacts as $usercontact){

      if($user['id'] == $usercontact['user_id']){

        foreach($contacts as $contact){

          if($contact['id'] == $usercontact['contact_id']){

            $list[] = [

                            //'Username' =>  $user['name_user'],
              'Contact' =>  $contact['name_contact'],
              'Contact_phone' =>  $contact['phone_contact'],
              'Contact_email' =>  $contact['mail_contact']

            ];
          }
        }
      }
    }    

  }
    //$user = User::all();
  return response($list);

}

//REGISTER CONTACT//

public function createContact(Request $request){

  $response = "";

    //Getting data from request
  $data = $request->getContent();

    //Verify data exists
  $data = json_decode($data);

  if($data!=""){
        //TODO: validate user input data 
    if($data){

            //Create user
      $contact = new Contact();


            //Required data
      $contact->name_contact = $data->contactname;
      $contact->phone_contact =$data->phone;
      $contact->mail_contact = $data->email;
            //Save contact
      try{

        $contact->save();

        $response = "OK. Data saved";
      }catch(\Exception $e){
        $response = $e->getMessage();
      }

    }

  }else{
    $response = "Wrong data";
  }



  return response($response);
}
public function editContact(Request $request){

  $respuesta = "";

  $datos = $request->getContent();
  $datos = json_decode($datos);

  $apiToken = $request->bearerToken();
  $contactID = Contact::where('api_token',$apiToken)->value('id');

   //Check if contact exists
  $contact = Contact::find($contactID);

  if($contact){

   if($datos){

               //Check if values exists on request
     if(isset($datos->email)){
      $contact->email_contact = $datos->email;
    }
    if(isset($datos->name)){
      $contact->name_contact = $datos->name;
    }
    if(isset($datos->phone)){
      $contact->phone_contact = $datos->phone;
    }

               //Save contact
    try{

      $contact->save();

      $respuesta = "OK";
    }catch(\Exception $e){
      $respuesta = $e->getMessage();
    }
  }else{
   $respuesta = "Incorrect data";
 }
}else{
 $response = "Contact not found";
}

return $contact;
}
public function deleteContact(Request $request){

  $respuesta = "";
  $data = $request->getContent();
  $data = json_decode($data);

  $userID = Contact::where('name_contact',$data->name)->value('id');
  $contact = Contact::find($userID);

  if($contact){

        //Delete contact
    try{

      $contact->delete();

      $respuesta = "Contact deleted";
    }catch(\Exception $e){
      $respuesta = $e->getMessage();
    }

  }else{
    $response = "Contact not found";
  }


  return response($userID);
}
}