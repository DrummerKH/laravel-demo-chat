<?php

class AccountController extends AuthorizedController
{
	/**
	 * Let's whitelist all the methods we want to allow guests to visit!
	 *
	 * @access   protected
	 * @var      array
	 */
	protected $whitelist = array(
		'getLogin',
		'postLogin',
		'getRegister',
		'postRegister'
	);

	/**
	 * Main users page.
	 *
	 * @access   public
	 * @return   View
	 */
	public function getIndex()
	{
		// Show the page.
		//
		return View::make('account/index')->with('user', Auth::user());
	}

	/**
	 *
	 *
	 * @access   public
	 * @return   Redirect
	 */
	public function postIndex()
	{
		// Declare the rules for the form validation.
		//
		$rules = array(
			'username' => 'Required',
			'email'      => 'Required|Email|Unique:users,email,' . Auth::user()->email . ',email',
		);

		// If we are updating the password.
		//
		if (Input::get('password'))
		{
			// Update the validation rules.
			//
			$rules['password']              = 'Required|Confirmed';
			$rules['password_confirmation'] = 'Required';
		}

		// Get all the inputs.
		//
		$inputs = Input::all();

		// Validate the inputs.
		//
		$validator = Validator::make($inputs, $rules);

		// Check if the form validates with success.
		//
		if ($validator->passes())
		{
			// Create the user.
			//
			$user =  User::find(Auth::user()->id);
			$user->username = Input::get('username');
			$user->email      = Input::get('email');

			if (Input::get('password') !== '')
			{
				$user->password = Hash::make(Input::get('password'));
			}

			$user->save();

			// Redirect to the register page.
			//
			return Redirect::to('account')->with('success', 'Account updated with success!');
		}

		// Something went wrong.
		//
		return Redirect::to('account')->withInput($inputs)->withErrors($validator->getMessageBag());
	}

	/**
	 * Login form page.
	 *
	 * @access   public
	 * @return   View
	 */
	public function getLogin()
	{

		// Are we logged in?
		//
		if (Auth::check())
		{
			return Redirect::to('account');
		}

		// Show the page.
		//
		return View::make('account/login');
	}

	public function getList()
	{
		if(!Auth::check()){
			return Redirect::to('account/login');
		}
		// Show the page.
		$users = User::whereRaw('role != ?', array('admin'))->get();
		//
		return View::make('account/users', ['users' => $users, 'user' => Auth::user()]);
	}

	public function getChat()
	{
		if(!Auth::check()){
			return Redirect::to('account/login');
		}
		$id = Input::get('id');

		$messages = Message::whereRaw('to_user_id = ?', array($id))->get();

		$messagesArray = [];
		foreach ($messages as $msg){
			$messagesArray[] = [
				'id' => $msg->id,
				'from_user_id' => $msg->from_user_id,
				'user' => $msg->fromUser->username,
				'message' => $msg->message,
			];
		}

		// Show the page.
		//
		return View::make('account/chat', [ 'user' => Auth::user(), 'toUser' => $id, 'messages' => json_encode($messagesArray)]);
	}

	/**
	 * Login form processing.
	 *
	 * @access   public
	 * @return   Redirect
	 */
	public function postLogin()
	{
		// Declare the rules for the form validation.
		//
		$rules = array(
			'email'    => 'Required',
			'password' => 'Required'
		);

		// Get all the inputs.
		//
		$email = Input::get('email');
		$password = Input::get('password');

		// Validate the inputs.
		//
		$validator = Validator::make(Input::all(), $rules);

		// Check if the form validates with success.
		//
		if ($validator->passes())
		{
			// Try to log the user in.
			//
			if (Auth::attempt(array('email' => $email, 'password' => $password)) || Auth::attempt(array('username' => $email, 'password' => $password)))
			{
				// Redirect to the users page.
				//
				return Redirect::to('account')->with('success', 'You have logged in successfully');
			}
			else
			{
				// Redirect to the login page.
				//
				return Redirect::to('account/login')->with('error', 'Email/password invalid.');
			}
		}

		// Something went wrong.
		//
		return Redirect::to('account/login')->withErrors($validator->getMessageBag());
	}

	/**
	 * User account creation form page.
	 *
	 * @access   public
	 * @return   View
	 */
	public function getRegister()
	{
		// Are we logged in?
		//
		if (Auth::check())
		{
			return Redirect::to('account');
		}

		// Show the page.
		//
		return View::make('account/register');
	}

	/**
	 * User account creation form processing.
	 *
	 * @access   public
	 * @return   Redirect
	 */
	public function postRegister()
	{
		// Declare the rules for the form validation.
		//
		$rules = array(
			'username'            => 'Required',
			'email'                 => 'Required|Email|Unique:users',
			'password'              => 'Required|Confirmed',
			'password_confirmation' => 'Required'
		);

		// Get all the inputs.
		//
		$inputs = Input::all();

		// Validate the inputs.
		//
		$validator = Validator::make($inputs, $rules);

		// Check if the form validates with success.
		//
		if ($validator->passes())
		{
			// Create the user.
			//
			$user = new User;
			$user->username = Input::get('username');
			$user->email      = Input::get('email');
			$user->password   = Hash::make(Input::get('password'));
			$user->save();

			// Redirect to the register page.
			//
			return Redirect::to('account/register')->with('success', 'Account created with success!');
		}

		// Something went wrong.
		//
		return Redirect::to('account/register')->withInput($inputs)->withErrors($validator->getMessageBag());
	}

	/**
	 * Logout page.
	 *
	 * @access   public
	 * @return   Redirect
	 */
	public function getLogout()
	{
		// Log the user out.
		//
		Auth::logout();

		// Redirect to the users page.
		//
		return Redirect::to('account/login')->with('success', 'Logged out with success!');
	}
}
