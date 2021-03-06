<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Http\Resources\MessagesResource;

class MessagesController extends Controller
{
    public function createMessage(Message $message){
        $message->create($this->validateMessage());
    }

    public function editMessage($id){
        Message::where('id',$id)->update(array('message'=>'This is the message edited'));
    }

    public function temporaryDeleteMessage(Message $message, $id){
        $deletable = $message->find($id);
        $deletable->update(array('status'=>'deleted'));
    }

    public function parmanetlyDeleteMessage(Message $message, $id){
        $parmanetly_deletable = $message->find($id);
        $parmanetly_deletable->delete();
    }
    
    public function markAsRead(Message $message, $id){
        $read_message = $message->find($id);
        $read_message->update(array('status'=>'read'));
    }

    public function getAllMessages(){
        return MessagesResource::collection(Message::join('users','users.id','messages.senders_id')->paginate(10));
    }

    public function getMySentMessages($id){
        return MessagesResource::collection(Message::join('users','users.id','messages.senders_id')->where('senders_id',$id)->get());
    }

    public function getMyRecievedMessages($id){
        return MessagesResource::collection(Message::join('users','users.id','messages.recievers_id')->where('recievers_id',$id)->get());
    }

    protected function validateMessage(){
        return request()->validate([
            'message'      => 'required',
            'senders_id'   => '',
            'recievers_id' => '',
            'status'       => 'required',
            'category_id'  => 'required'
        ]);
    }
}
