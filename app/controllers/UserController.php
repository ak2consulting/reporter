<?php

class UserController extends BaseController {

    protected $layout = 'layouts.master';


    /**
    |
    |
    */
    public function showUserProfile() {

        // Check if we already logged in
        if ( !Auth::check() ) {

            // Redirect to homepage
            return Redirect::to('login')->withErrors("You must login!");

        }

        // Show the login page
        return View::make('user/profile');
    }


    public function showUserCreate() {


        if( Auth::check() ){
            return Redirect::to('')->with('warn', 'Authenticated users can not create additional users');
        }

        // Show the login page
        return View::make('user/create');

    }


    public function doUserCreate() {

        if( Auth::check() ){
            return Redirect::to('')->with('warn', 'Authenticated users can not create additional users');
        }

        $userdata = array(
            'username' => Input::get('username'),
            'email'    => Input::get('email'),
            'password' => Input::get('password')
        );

        // Declare the rules for the form validation.
        $rules = array(
            'username'  => array('required', 'unique:users,username'),
            'email'     => array('required', 'email', 'unique:users,email'),
            'password'  => array('required', 'min:8, AlphaNum')
        );

        // Validate the inputs.
        $validator = Validator::make($userdata, $rules);

        // Check if the form validates with success.
        if ($validator->passes()) {




                // get NOW
                $now = new DateTime('NOW');
                // all things went OK, insert into the database


                $userRecord = array("username"=>$userdata["username"],
                        "email"=>$userdata["email"],
                        "password"=>Hash::make($userdata["password"]),
                        "created_at"=>new MongoDate( $now->getTimestamp() ),// format(DateTime::ISO8601),
                        "permission_level" => 0);

                $insertID = LMongo::collection('users')->insert($userRecord);


            //TODO: error handeling? - waht happens on unique violation?


            // Try to log the user in.
            if (Auth::attempt($userdata, true)) {
                // Redirect to homepage
                return Redirect::to('/data')->with('success', 'User created OK!');
            } else {

                // Redirect to the login page.
                return Redirect::to('login')->withErrors(array('password' => 'Password invalid'))->withInput(Input::except('password'));
            }
        }

        // Something went wrong. Give the view the error and provide all fields pre-populated except password
        return Redirect::to('user/create')->withErrors($validator)->withInput(Input::except('password'));

    }
}
