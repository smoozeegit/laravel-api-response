<?php

namespace serdud\ApiResponse;

use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{
    private $data;
    private $status;

    /**
     * Response constructor.
     *
     * @param mixed $data
     * @param int $status
     */
    public function __construct($data, int $status = HttpResponse::HTTP_OK)
    {
        $this->status = $status;
        $this->handleData($data);
    }

    /**
     * @param mixed $data
     */
    protected function handleData($data)
    {
        if ($data instanceof Model || $data instanceof Collection) {
            $this->data = ['data' => $data->toArray()];
        } elseif ($data instanceof MessageBag) {
            $errors = [];
            foreach ($data->toArray() as $error) {
                $errors[] = $error[0];
            }
            $this->data = ['errors' => $errors];
            $this->status = HttpResponse::HTTP_BAD_REQUEST;
        } elseif (is_array($data) && (array_key_exists('message', $data) || array_key_exists('data', $data) || array_key_exists('error', $data))) {
            $this->data = $data;
        } else {
            $this->data = ['data' => $data];
        }
    }

    /**
     * @return JsonResponse
     */
    public function getResponse()
    {
        return response()->json($this->data, $this->status);
    }
}
