<?php

namespace App\Http\Data_object_transfer;
use App\Http\Requests\Auth\RegisterRequest;

class UserDto
{
     public $name;
     public $email;
     public $password;
     public $phone;
     public $role;
     public $provider;

     public function __construct($name, $email, $password, $phone, $role, $provider)
     {
         $this->name = $name;
         $this->email = $email;
         $this->password = $password;
         $this->phone = $phone;
         $this->role = $role;
         $this->provider = $provider;
     }

     public function Toarray()
     {
       
         return [
             'name' => $this->name,
             'email' => $this->email,
             'password' => $this->password,
             'phone' => $this->phone,
             'role' => $this->role,
             'provider' => $this->provider,
         ];
     }
}

    
