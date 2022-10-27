<?php

use serdud\ApiResponse\Response;

/**
 * @param     $data
 * @param int $status
 *
 * @return \Symfony\Component\HttpFoundation\JsonResponse
 */
function jsonResponse($data, int $status = 200)
{
    return (new Response($data, $status))->getResponse();
}
