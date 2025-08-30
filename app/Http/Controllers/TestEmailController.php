<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function testEmail()
    {
        try {
            Mail::raw('Test email from My Clinic Center', function($message) {
                $message->to('gokhan@lim.com.tr')
                        ->subject('Test Email - My Clinic Center');
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent to log file'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

