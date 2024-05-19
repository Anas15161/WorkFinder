<?php

namespace App\Http\Controllers;

use App\Mail\SendJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class EmailController extends Controller
{
    public function send(Request $request){
        dd($request->all());

        $this->validate($request, [
            'your_name' => 'required|string',
            'your_email' => 'required|email',
            'friend_name' => 'required|string',
            'friend_email' => 'required|email',
            'job_id' => 'required', // Assuming job_id is also required
            'job_slug' => 'required', // Assuming job_slug is also required
            // Add any other validation rules as needed
        ]);

        // Check if the required fields are not null before accessing them
        $yourName = $request->get('your_name');
        $yourEmail = $request->get('your_email');
        $friendName = $request->get('friend_name');
        $jobId = $request->get('job_id');
        $jobSlug = $request->get('job_slug');
        // Check if any of the required fields are null
        if ($yourName && $yourEmail && $friendName && $jobId && $jobSlug) {
            $homeUrl = url('/');
            $jobUrl = $homeUrl . '/job/' . $jobId . '/' . $jobSlug;

            $data = array(
                'your_name' => $yourName,
                'your_email' => $yourEmail,
                'friend_name' => $friendName,
                'title' => $request->get('title'),
                'cname' => $request->get('cname'),
                'position' => $request->get('position'),
                'jobUrl' => $jobUrl
            );

            $emailTo = $request->get('friend_email');

            try{
                Mail::to($emailTo)->send(new SendJob($data));
                return redirect()->back()->with('jobmsg', 'Job sent Successfully!');
            } catch(\Exception $e){
                return redirect()->back()->with('error_msg', 'Something went wrong. Please try again later.');
            }
        } else {
            return redirect()->back()->with('error_msg', 'Required fields are missing.');
        }
    }

}
