<?php

use App\Job;
use App\MovingStock;
use App\Notifications\SendPushMessageToTopic;
use \App\Repositories\GeneralRepository;
use \App\Repositories\AccessControl\SysAdminRepository;
use App\Store;
use Benwilkins\FCM\FcmMessage;
use Carbon\Carbon;
use Cocur\BackgroundProcess\BackgroundProcess;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


function _GET($endpoint, $payload = [], $log_context = []){
    $response = file_get_contents(onlineBase().'api/data/'.$endpoint);
    if(isJson($response)) return json_decode($response,true);
    return [];
}

function _POST($endpoint, $payload = [], $log_context = []){
    $response =  curlHelpers('api/data/'.$endpoint,'POST',$payload,$log_context);
    if(isJson($response)) return json_decode($response,true);
    return [];
}



/**
 * Set Controller Default Layout And Render Content
 *
 * @param string $content
 * @return \Illuminate\Http\Response
 */
function setPageContent($pageblade, $data = array(), $layout = 'layouts.app2')
{
    return view($layout, ['content' => view($pageblade, $data)]);
}

function month_year($time = false, $pad = false)
{
    if (!$time) $time = time() + time_offset();
    else $time = strtotime($time);
    if ($pad) $pad = ". h:i:s A";
    else $pad = "";
    return date('F, Y' . $pad, $time);
}

function time_offset()
{
    return 0;
}


function normal_case($str)
{
    return ucwords(str_replace("_", " ", snake_case(str_replace("App\\", "", $str))));
}

function status($status){
    $status = strtolower($status);
    if($status == "draft"){
        return label(ucwords($status),"info");
    }else if($status == "pending"){
        return label(ucwords($status),"primary");
    }else{
        return label(ucwords($status),"success");
    }
}

function online_status($status){
    $status = strtolower($status);
    if($status == "processing"){
        return label(ucwords($status),"warning");
    }else if($status == "processed"){
        return label(ucwords($status),"primary");
    } else if($status == "complete"){
        return label(ucwords($status),"success");
    }else{
        return label(ucwords($status),"danger");
    }
}



function generateGroupProductModalRetail($group_id){
    $group = \App\StockGroup::find($group_id);
    $data = '<tbody>';
    $nos = \App\RetailNearOs::with(['stock','stock.productCategory','supplier'])
        ->where('group_os_id',$group_id)->get();
    foreach($nos as $no){
        $data.='
                    <tr>
                         <td><input type="checkbox" name="selected_checkbox[]" value="'.$no->id.'" class="nearosbox"/></td>
                         <td>'.$no->stock->name.'</td>
                         <td>'.($no->stock->category_id ? $no->stock->productCategory->name : 'N/A').'</td>
                         <td>'.$no->stock->box.'</td>
                         <td>'.$no->stock->cartoon.'</td>
                         <td>'.$no->threshold_type.'</td>
                         <td>'.$no->qty_to_buy.'</td>
                         <td>'.$no->supplier->name.'</td>
                         <td>'.$no->threshold_value.'</td>
                         <td>'.$no->current_qty.'</td>
                         <td>'.$no->current_sold.'</td>
                         <td>'.$no->last_qty_purchased.'</td>
                         <td>'.convert_date2($no->last_purchase_date).'</td>
                    </tr>
               ';
    }
    $data.='</table>';
    $html = '<div class="table-responsive"><table class="table table-bordered table-striped">
                <thead>
                      <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Box</th>
                            <th>Cartoon</th>
                            <th>Threshold Type</th>
                            <th>Qty to Buy</th>
                            <th>Last Supplier</th>
                            <th>Threshold Value</th>
                            <th>Stock Quantity</th>
                            <th>Total Sold</th>
                            <th>Last Qty Pur.</th>
                            <th>Last Date Pur.</th>
                        </tr>
                </thead>'.$data.'
            </table></div>';

    $template = '<div class="modal" id="grouped_'.$group_id.'"  role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">'.$group->name.'</h5>
      </div>
      <div class="modal-body">
     '.$html.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';
    return $template;
}


function generateGroupProductModalMove($group_id){
    $group = \App\StockGroup::find($group_id);
    $data = '<tbody>';
    $nos = MovingStock::with([
        'stockGroup',
        'stock',
        'supplier',
        'stock.stockOpening',
        'stock.productCategory',
        'stock.stockBatches'=>function($q){
            $q->select(
                'product_id',
                DB::raw( 'SUM(wholesales) as ws'),
                DB::raw( 'SUM(bulksales) as bs'),
                DB::raw( 'SUM(quantity) as ms'),
                DB::raw( 'SUM(retail) as rt'),
                DB::raw( 'SUM(cost_price * (wholesales+bulksales+quantity)) as total_cost_price'),
                DB::raw( 'SUM(retail_cost_price * retail) as total_retail_cost_price')
            )
                ->orderBy('id','DESC')
                ->where(function($qq){
                    $qq->orwhere('retail','>',0)->orwhere('wholesales','>',0)->orwhere('quantity','>',0)->orwhere('bulksales','>',0);
                })
                ->groupBy('product_id');
        }
    ])
        ->where('group_os_id',$group_id)->get();
    foreach($nos as $no){
        $data.='
                    <tr>
                         <td>'.(isset($no->stock->name) ? $no->stock->name : 'N/A').'</td>
                         <td>'.($no->stock->category_id ? (isset($no->stock->productCategory->name) ? $no->stock->productCategory->name : 'N/A' ) : 'N/A').'</td>
                         <td>'.$no->stock->box.'</td>
                         <td>'.$no->stock->cartoon.'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : number_format($no->stock->stockOpening->first()->average_cost_price,2)).'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : number_format($no->stock->stockOpening->first()->average_retail_cost_price,2)).'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : $no->stock->stockOpening->first()->retail."(".round(abs($no->stock->stockOpening->first()->retail/$no->stock->box))." Box)").'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : round($no->stock->stockOpening->first()->total - ($no->stock->stockOpening->first()->retail/$no->stock->box))).'</td>
                         <td>'.($no->daily_qty_sold).'</td>
                         <td>'.($no->average_inventory).'</td>
                         <td>'.($no->turn_over_rate).'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : number_format(($no->stock->stockOpening->first()->average_cost_price * round($no->stock->stockOpening->first()->total - ($no->stock->stockOpening->first()->retail/$no->stock->box))),2)).'</td>
                         <td>'.(!$no->stock->stockOpening->first() ? number_format(0,2) : number_format(($no->stock->stockOpening->first()->average_retail_cost_price * $no->stock->stockOpening->first()->retail),2)).'</td>
                         <td>'.(isset($no->supplier->name) ? $no->supplier->name : 'N/A').'</td>
                    </tr>
               ';
    }
    $data.='</table>';
    $html = '<div class="table-responsive"><table class="table table-bordered table-striped">
                <thead>
                      <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Box</th>
                            <th>Cartoon</th>
                            <th>Av.Cost price</th>
                            <th>Av. Rt .Cost price</th>
                            <th>Rt Qty</th>
                            <th>WS,BS,MS Qty</th>
                            <th>Daily Qty Sold</th>
                            <th>Av. Inventory</th>
                            <th>Turn Over rate</th>
                            <th>Worth</th>
                            <th>RT Worth</th>
                            <th>Last Supplier</th>
                        </tr>
                </thead>'.$data.'
            </table></div>';

    $template = '<div class="modal" id="grouped_'.$group_id.'"  role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">'.$group->name.'</h5>
      </div>
      <div class="modal-body">
     '.$html.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';
    return $template;
}


function label($text, $type = 'default', $extra = 'sm')
{
    return '<span class="label label-' . $type . '" label-form>' . $text . '</span>';
}


function label_status($text, $type = 'default', $extra = 'sm')
{
    $func = "label_".$type;
    return $func($text,$type,$extra);
}

function label_success($text, $extra = 'sm')
{
    return '<span class="label label-success' . '" label-form>' . $text . '</span>';
}

function label_complete($text, $extra = 'sm')
{
    return '<span class="label label-success' . '" label-form>' . $text . '</span>';
}

function label_warning($text, $extra = 'sm')
{
    return '<span class="label label-warning' . '" label-form>' . $text . '</span>';
}


function label_pending($text, $extra = 'sm')
{
    return '<span class="label label-warning' . '" label-form>' . $text . '</span>';
}

function label_approved($text, $extra = 'sm')
{
    return '<span class="label label-primary' . '" label-form>' . $text . '</span>';
}

function label_ready($text, $extra = 'sm')
{
    return '<span class="label label-success' . '" label-form>' . $text . '</span>';
}

function label_unapproved($text, $extra = 'sm')
{
    return '<span class="label label-danger' . '" label-form>' . $text . '</span>';
}

function label_failed($text, $extra = 'sm')
{
    return '<span class="label label-danger' . '" label-form>' . $text . '</span>';
}

function label_default($text, $extra = 'sm')
{
    return '<span class="label label-default' . '" label-form>' . $text . '</span>';
}


function alert_success($msg)
{

    return alert('success', $msg);
}

function alert_info($msg)
{
    return alert('info', $msg);
}

function alert_warning($msg)
{
    return alert('warning', $msg);
}

function alert_error($msg)
{
    return alert('danger', $msg);
}

function alert($status, $msg)
{
    return '<div class="alert alert-' . $status . '">' . $msg . '</div>';
}

function money($amt)
{
    return number_format($amt, 2);
}

/**
 * Return a capitalised string
 *
 * @return string
 * @param string $string
 */
function toCap($string)
{
    return strtoupper(strtolower($string));
}

/**
 * Return a small letter string
 *
 * @return string
 * @param string $string
 */
function toSmall($string)
{
    return strtolower($string);
}

/**
 * Return a sentence case string
 *
 * @return string
 * @param string $string
 */
function toSentence($string)
{
    return ucwords(strtolower($string));
}

function generateRandomString($randStringLength)
{
    /*
    $timestring = microtime();
    $secondsSinceEpoch = (integer)substr($timestring, strrpos($timestring, " "), 100);
    $microseconds = (double)$timestring;
    $seed = mt_rand(0, 1000000000) + 10000000 * $microseconds + $secondsSinceEpoch;
    mt_srand($seed);
    $randstring = "";
    for ($i = 0; $i < $randStringLength; $i++) {
        $randstring .= mt_rand(0, 9);
    }
    return ($randstring);
    */
    return time();
}


/**
 * Get IDs of the Work Groups this User has been granted permission to work on.
 * @return array
 */

function getRandomString_AlphaNum($length)
{
    //Init the pool of characters by category
    $pool[0] = "ABCDEFGHJKLMNPQRSTUVWXYZ";
    $pool[1] = "23456789";
    return randomString_Generator($length, $pool);
}   //END getRandomString_AlphaNum()


function randomString_Num($length)
{
    //Init the pool of characters by category
    $pool[0] = "0123456789";
    return randomString_Generator($length, $pool);
}

function getRandomString_AlphaNumSigns($length)
{
    //Init the pool of characters by category
    $pool[0] = "ABCDEFGHJKLMNPQRSTUVWXYZ";
    $pool[1] = "abcdefghjkmnpqrstuvwxyz";
    $pool[2] = "23456789";
    $pool[3] = "-_";
    return randomString_Generator($length, $pool);
}

function randomString_Generator($length, $pools)
{
    $highest_pool_index = count($pools) - 1;
    //Now generate the string
    $finalResult = "";
    $length = abs((int)$length);
    for ($counter = 0; $counter < $length; $counter++) {
        $whichPool = rand(0, $highest_pool_index);    //Randomly select the pool to use
        $maxPos = strlen($pools[$whichPool]) - 1;    //Get the max number of characters in the pool to be used
        $finalResult .= $pools[$whichPool][mt_rand(0, $maxPos)];
    }
    return $finalResult;
}

/**
 * The only difference between this and date is that it works with the env time offet
 * @param $format
 * @param $signed_seconds
 * @return bool|string
 */
if (!function_exists("now")) {
    function now($format = 'Y-m-d H:i:s', $signed_seconds = 0)
    {
        return date($format, ((time() + (env('TIME_OFFSET_HOURS', 0) * 60)) + $signed_seconds));
    }
}


function getPaperAttributes($paperSize)
{
    $paperSize = strtolower($paperSize);
    switch ($paperSize){
        case "a4l":
            $size = "A4";
            $orientation = "Landscape";
            $startX = 785;
            $startY = 570;
            $font = 9;
            break;
        case "a4p":
            $size = "A4";
            $orientation = "Portrait";
            $startX = 540;
            $startY = 820;
            $font = 9;
            break;
        case "a3l":
            $size = "A3";
            $orientation = "Landscape";
            $startX = 1130;
            $startY = 820;
            $font = 9;
            break;
        case "a3p":
            $size = "A3";
            $orientation = "Portrait";
            $startX = 785;
            $startY = 1165;
            $font = 9;
            break;
        case "us-sff":
            $size = "U.S. Standard Fanfold";
            $orientation = "Landscape";
            $startX = 692;
            $startY = 585;
            $font = 9;
            break;
        default:
            $size = "A4";
            $orientation = "Landscape";
            $startX = 785;
            $startY = 570;
            $font = 9;
            break;
    }
    return [$size, $orientation, $startX, $startY, $font];
}

function softwareStampWithDate($width = "100px") {
    //<img src='". URL::asset('img/logo.png')."' width='".$width."'>
    return "Generated @". date('Y-m-d H:i A') ;
}

function string_to_secret(string $string = NULL)
{
    if (!$string) return NULL;

    $length = strlen($string);
    $visibleCount = (int) round($length / 4);
    $hiddenCount = $length - ($visibleCount * 2);

    return substr($string, 0, $visibleCount) . str_repeat('*', $hiddenCount) . substr($string, ($visibleCount * -1), $visibleCount);
}

function split_name($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));
    return array("firstname" => $first_name, "lastname" => $last_name);
}

function str_date($time = false, $pad = false)
{
    if (!$time) $time = time() + time_offset();
    else $time = strtotime($time);
    if ($pad) $pad = ". h:i:s A";
    else $pad = "";
    return date('l, F jS, Y' . $pad, $time);
}

function str_date2($time = false, $pad = false)
{
    if (!$time) $time = time() + time_offset();
    else $time = strtotime($time);
    if ($pad) $pad = ". h:i:s A";
    else $pad = "";
    return date('D, F jS, Y' . $pad, $time);
}

function convert_date($date){
    return date('D, F jS, Y', strtotime($date));
}

function convert_date_with_time($date){
    return date('D, F jS, Y h:i a', strtotime($date));
}

function convert_date2($date){
    return date('Y/m/d', strtotime($date));
}

function cur_format($number){
    return number_format($number,2);
}

function onlineBase(){
    return env('CURL_BASE_URL', 'https://admin.generaldrugcentre.com/');
}

//laravel curl helper code start here dont have to use composer

if (!function_exists('curlHelpers')) {
    function curlHelpers($endpoint, $method = 'POST', $payload = [], $log_context = [])
    {
        $url = env('CURL_BASE_URL', onlineBase()) . $endpoint;
        $payload = !empty($payload) ? $payload : request()->all();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => (bool)env('CURLOPT_RETURNTRANSFER', true),
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => (int)env('CURLOPT_MAXREDIRS', 30),
            CURLOPT_TIMEOUT => (int)env('CURLOPT_TIMEOUT', 50),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        logInit();

        if ($err) {
            logError($url, $payload, $log_context, $err);
            return response()->json("cURL Error #:" . $err, 500);
        } else {
            logInfo($url, $payload, $log_context, $response);
            return $response;
        }
    }
}

if (!function_exists('logInit')) {
    function logInit()
    {
        \Log::debug(null);

        $custom_config = [
            'curl' => [
                'driver' => 'single',
                'path' => storage_path('logs/curl/curl.log'),
                'level' => 'info',
            ],
            'curl-info' => [
                'driver' => 'daily',
                'path' => storage_path('logs/curl/info/curl-info.log'),
                'level' => 'info',
            ],
            'curl-error' => [
                'driver' => 'daily',
                'path' => storage_path('logs/curl/error/curl-error.log'),
                'level' => 'error',
            ]
        ];
        \Illuminate\Support\Facades\Config::set('logging.channels', array_merge(is_null(\Illuminate\Support\Facades\Config::get('logging.channels')) ? [] : \Illuminate\Support\Facades\Config::get('logging.channels'), $custom_config));
    }
}

if (!function_exists('getExecutionId')) {
    function getExecutionId()
    {
        return date('YmdHis') . '-' . (string)\Illuminate\Support\Str::uuid() . '-' . \Illuminate\Support\Str::random(12);
    }
}

if (!function_exists('logError')) {
    function logError($url, $payload, $log_context, $err)
    {
        $execId = getExecutionId();
        $log = \Log::stack(['curl', 'curl-error']);
        //$log->error('cURL Exec ' . $url . '.' . PHP_EOL . 'Payload:' . prettyResponse($payload) . PHP_EOL . 'Exec ID: ' . $execId . PHP_EOL, $log_context);
        //$log->error('cURL Error #:' . $err . PHP_EOL . 'Exec ID: ' . $execId . PHP_EOL, $log_context);
        logDB($url, $payload, 'cURL Error #:' . $err);
    }
}

if (!function_exists('logInfo')) {
    function logInfo($url, $payload, $log_context, $response)
    {
        $execId = getExecutionId();
        $log = \Log::stack(['curl', 'curl-info']);
        //$log->info('cURL Exec ' . $url . '.' . PHP_EOL . 'Payload:' . prettyResponse($payload) . PHP_EOL . 'Exec ID: ' . $execId . PHP_EOL, $log_context);
        //$log->info('cURL Response:' . prettyResponse($response) . PHP_EOL . 'Exec ID: ' . $execId . PHP_EOL, $log_context);
        logDB($url, $payload, $response);
    }
}

if (!function_exists('logDB')) {
    function logDB($url, $payload, $response)
    {
        checkDB();
        \DB::table('curl_logs')->insert(
            [
                'url' => $url,
                'payload' => json_encode($payload),
                'response' => $response,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}

if (!function_exists('checkDB')) {
    function checkDB()
    {
        if (!\Schema::hasTable('curl_logs')) {
            \Schema::create('curl_logs', function ($table) {
                $table->increments('id');
                $table->string('url');
                $table->text('payload')->nullable();
                $table->text('response')->nullable();
                $table->timestamps();
            });
        }
    }
}

if (!function_exists('prettyResponse')) {
    function prettyResponse($json)
    {
        if (!is_string($json)) {
            return PHP_EOL . json_encode($json, JSON_PRETTY_PRINT);
        }
        if (isJson($json)) {
            return PHP_EOL . json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
        }
        return $json;
    }
}

if (!function_exists('isJson')) {
    function isJson($string)
    {
        if(is_array($string) || is_object($string)) return true;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}


function pushNotificationToDevice($payload,$title, $body, $tokens){
    $message = new FcmMessage();
    $payload['title'] = $title;
    $payload['body'] = $body;
    $message->content([
        'title'        => $title,
        'body'         => $body,
        'sound'        => 'default', // Optional
        'icon'         => 'default', // Optional
        'click_action' => '.MainActivity'
    ])
        ->to($tokens, $recipientIsTopic = false)
        ->data(['json'=>$payload])->priority(FcmMessage::PRIORITY_HIGH);

    Notification::send(['fcm'],new SendPushMessageToTopic($message,$tokens));
}

function pushNotificationToTopic($payload,$title, $body){
    $message = new FcmMessage();
    $payload['title'] = $title;
    $payload['body'] = $body;
    $message->to('bluekhdpushtopic', $recipientIsTopic = true)
        ->content([
            'title'        => $title,
            'body'         => $body,
            'sound'        => 'default', // Optional
            'icon'         => 'default', // Optional
            'click_action' => '.MainActivity'
        ])
        ->priority(FcmMessage::PRIORITY_HIGH)
        ->data(['json'=>$payload]);

    Notification::send(['fcm'],new SendPushMessageToTopic($message));

}


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}



function addFrontPageSettings($key,$value)
{
    $settings = \App\Frontpagesetting::where('key',$key)->first();

    if($settings)
    {
        $settings->value = $value;
        $settings->update();
    }
    else
    {
        \App\Frontpagesetting::create(
          [
              'key' => $key,
              'value' => $value
          ]
        );
    }

}

function getFrontPageSettings($key)
{
    $settings = \App\Frontpagesetting::where('key',$key)->first();

    if($settings) return $settings->value;

    return NULL;
}


function file_upload_max_size() {
    static $max_size = -1;

    if ($max_size < 0) {
        // Start with post_max_size.
        $post_max_size = parse_size(ini_get('post_max_size'));
        if ($post_max_size > 0) {
            $max_size = $post_max_size;
        }

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $upload_max = parse_size(ini_get('upload_max_filesize'));
        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload_max;
        }
    }
    return humanFileSize($max_size);
}

function parse_size($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    else {
        return round($size);
    }
}

function humanFileSize($size,$unit="") {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
    if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
    if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
    return number_format($size)." bytes";
}


function human_filesize($size, $precision = 2) {
    static $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision).$units[$i];
}



function getMaximumFileUploadSize()  
{  
    return human_filesize(min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize'))));  
}  

/**
* This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
* 
* @param string $sSize
* @return integer The value in bytes
*/
function convertPHPSizeToBytes($sSize)
{
    //
    $sSuffix = strtoupper(substr($sSize, -1));
    if (!in_array($sSuffix,array('P','T','G','M','K'))){
        return (int)$sSize;  
    } 
    $iValue = substr($sSize, 0, -1);
    switch ($sSuffix) {
        case 'P':
            $iValue *= 1024;
            // Fallthrough intended
        case 'T':
            $iValue *= 1024;
            // Fallthrough intended
        case 'G':
            $iValue *= 1024;
            // Fallthrough intended
        case 'M':
            $iValue *= 1024;
            // Fallthrough intended
        case 'K':
            $iValue *= 1024;
            break;
    }
    return (int)$iValue;
}      

