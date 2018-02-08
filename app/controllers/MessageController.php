<?php

class MessageController extends AuthorizedController
{

    public function getRemove()
    {

        $id = Input::get('id');
        $message = Message::find($id);
        $message->delete();

        return json_encode(['success' => 'true']);
        // Show the page.[]
        //
    }

    public function getIndex()
    {
        if(!Auth::check()){
            return Redirect::to('account/login');
        }
//		print_r(Auth::user()->role);die;
        if(Auth::user()->role !== 'admin'){
            return Redirect::to('account/login');
        }
        // Show the page.
        $messages = Message::get();
        //
        return View::make('account/messages', ['messages' => $messages, 'user' => Auth::user()]);
    }

}