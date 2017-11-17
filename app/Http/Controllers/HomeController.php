<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;

use App\Users;
use App\Registration;
use App\Events;
use App\Paper;

class HomeController extends Controller
{
    public function index()
    {
        return view('Home');
    }

    public function register(Request $request)
    {

        if(!Users::where('email', $request->input('email'))->first())
        {
            $name = $request->input('name');
            $email = $request->input('email');
            $newUser = new Users();
            $newUser->name = $request->input('name');
            $newUser->college = $request->input('college');
            $newUser->dept = $request->input('dept');
            $newUser->email = $request->input('email');
            $newUser->phone = $request->input('phone');
            $newUser->year = $request->input('year');
            $newUser->save();

            $lastUser = Users::orderBy('id', 'desc')->first();

            foreach($request->input('event') as $event)
            {
                $newRecord = new Registration();
                $newRecord->user_id = $lastUser->id;
                $newRecord->e_id = $event;
                $newRecord->save();
            }

            Mail::send('email', ['name'=>$request->input('name'), 'event' => $request->input('event')], function ($message) use ($name, $email)
            {
                $message->to( $email , $name)->from('admin@salvationz2k16.in')->subject('Salvation Registration Successful');
            });

            return $this->success();
        }
        else
            return $this->failed();
    }

    public function getPaper()
    {
        return view('PaperPresentation');
    }

    public function postPaper(Request $request)
    {
        $status = true;

        if(User::where('email', $request->input('email')->first()))
            $status=false;

        if(!$status)
            return view('Paper.Failed');


        $newPaper = new Paper();
        $newPaper->title = $request->input('title');
        $newPaper->abstract = $request->input('abstract');
        $newPaper->save();

        $fileName = $newPaper->id. '.' .
            $request->file('image')->getClientOriginalExtension();
        $newPaper->file = '/public/uploads/'.$fileName;
        $newPaper->save();
        $request->file('paper')->move(base_path() . '/public/uploads/', $fileName);
        return view('Paper.Success');

    }


    public function success()
    {
        return view('Success');
    }

    public function failed()
    {
        return view('Failed');
    }
}