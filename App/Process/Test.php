<?php
/**
 * Created by 2020/3/20 0020 19:30
 * User: yuansai chen
 */
namespace App\Process;
use App\Base;
use Swoole\Process;

class Test extends  Base{

    public function run()
    {

        $num=1000;

      for ($n = 1; $n <= $num; $n++) {
            $process = new Process(function () use ($n) {
                echo 'Child #' . getmypid() . " start and sleep {$n}s" . PHP_EOL;
                sleep($n);
                echo 'Child #' . getmypid() . ' exit' . PHP_EOL;
            });
            $process->start();
        }
        dd('child id is -'.$process->pid);

        for ($n = $num; $n--;) {
            $status = Process::wait(true);
            echo "Recycled #{$status['pid']}, code={$status['code']}, signal={$status['signal']}" . PHP_EOL;
        }
        echo 'Parent #' . getmypid() . ' exit' . PHP_EOL;
    }
    
     /**
     * curl 请求
     * @param  string  $method  //POST GET PUT DELETE
     * @param  string  $url
     * @param  array  $data
     * data  [
     * 'headers' => [
     * 'Accept' => 'application/json',
     * ],
     * 'verify'  => false,
     * 'params'    => $data
     *  or
     * 'json'    => $data
     * ]
     * @return bool|string
     */
    public static function request(string $method, string $url, array $data)
    {
        $params      = $data['params'] ?? $data;
        $verify      = $data['verify'] ?? false;
        $timeout     = $data['timeout'] ?? 1;
        $headerRes   = $data['headers'] ?? [
                "Content-type" => "application/json", "Accept" => "application/json"
            ];
        $headerArray = [];
        foreach ($headerRes as $k => $v) {
            $headerArray[] = $k.':'.$v;
        }
        if ($method == "GET") {
            $url = $url.'?'.http_build_query($params);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $verify);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $verify);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
