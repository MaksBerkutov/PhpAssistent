<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use AES;
use Exception;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $data = $request->json()->all();
            Log::debug($data);
            $message = $data['message'] ?? throw new Exception( $request->json());
            $iv = $data['IV'] ?? throw new Exception('Field [IV] not found');
            $name = $data['name'] ?? throw new Exception('Field [name] not found');
            $decryptMessage = AES::Decrypt($message, $iv);
            $request->replace(['message' => $decryptMessage,'name'=>$name]);
            response(['message' => "OKAY"], Response::HTTP_OK)->send();

            return $next($request);
        }
        catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
