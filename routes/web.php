<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', function () {
    return view('chat');
});

Route::get('/chat/{roomCode}', function ($roomCode) {
    return view('chat', ['roomCode' => $roomCode]);
});

Route::get('/zmq-status', function () {
    $status = [
        'zmq_extension' => extension_loaded('zmq'),
        'publisher' => false,
        'subscriber' => false,
        'message' => '',
    ];

    if (!$status['zmq_extension']) {
        $status['message'] = 'ZMQ extension not loaded';
        return response()->json($status, 500);
    }

    try {
        $context = new ZMQContext();
        
        try {
            $publisher = $context->getSocket(ZMQ::SOCKET_PUB);
            $publisher->connect(config('queue.connections.zmq.publisher_endpoint', 'tcp://127.0.0.1:5555'));
            $status['publisher'] = true;
            $publisher = null;
        } catch (Exception $e) {
            $status['message'] .= 'Publisher connection failed: ' . $e->getMessage() . '; ';
        }

        // Test subscriber conns (port 5556)
        try {
            $subscriber = $context->getSocket(ZMQ::SOCKET_SUB);
            $subscriber->connect(config('queue.connections.zmq.subscriber_endpoint', 'tcp://127.0.0.1:5556'));
            $subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, '');
            $status['subscriber'] = true;
            $subscriber = null;
        } catch (Exception $e) {
            $status['message'] .= 'Subscriber connection failed: ' . $e->getMessage();
        }

        if ($status['publisher'] && $status['subscriber']) {
            $status['message'] = 'ZMQ Broker is running and accepting connections';
            return response()->json($status, 200);
        } else {
            return response()->json($status, 503);
        }

    } catch (Exception $e) {
        $status['message'] = 'Error: ' . $e->getMessage();
        return response()->json($status, 500);
    }
});

