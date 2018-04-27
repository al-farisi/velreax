<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Response;
use \Illuminate\Http\Response as HttpResponse;

class ApiController extends Controller
{
    
    public function __construct()
    {
        $this->beforeFilter('auth', ['on'=>'post']);
    }

    protected $statusCode = HttpResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondWithPagination(Paginator $paginate, $data, $message)
    {
        $data = array_merge($data,[
            'paginator'=>[
                'total_count'=>$paginate->total(),
                'total_pages'=>ceil($paginate->total() / $paginate->perPage()),
                'current_page'=>$paginate->currentPage(),
                'limit'=>$paginate->perPage()
            ]
        ]);

        return $this->response([
            'status'=>'success',
            'status_code'=>HttpResponse::HTTP_OK,
            'message'=>$message,
            'data'=>$data
        ]);
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->response([
            'status'=>'error',
            'status_code'=> HttpResponse::HTTP_NOT_FOUND,
            'message'=>$message
        ]);
    }

    public function respondInternalError($message)
    {
        return $this->response([
            'status'=>'error',
            'status_code'=> HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>$message
        ]);
    }

    public function respondValidationError($message, $error)
    {
        return $this->response([
            'status'=>'error',
            'status_code'=> HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message'=>$message,
            'data'=>$errors
        ]);
    }

    public function response($data, $headers=[])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithErrors($message)
    {
        return $this->response([
            'status'=>'error',
            'status_code'=> HttpResponse::HTTP_UNAUTHORIZED,
            'message'=>$message
        ]);
    }
}
