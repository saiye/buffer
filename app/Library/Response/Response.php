<?php

namespace App\Library\Response;

use App\Library\Contract\Response as BaseResponse;

class Response implements BaseResponse
{
    use ResponseTrait;

    public function end(): void
    {
        foreach ($this->header as $k => $v) {
            header($k . ': ' . $v);
        }
        if (!key_exists('Content-Length', $this->header)) {
            header('Content-Length: ' . strlen($this->content));
        }
        echo $this->content;
    }


}
