<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Message extends Eloquent {

    protected $table = 'messages';

    public function fromUser()
    {
        return $this->belongsTo('User',  'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo('User',  'to_user_id');
    }

}