<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Amp\Socket;
use Amp\ByteStream;
use Amp\Socket\ClientTlsContext;
use Amp\Socket\ConnectContext;
use League\Uri\Http;
use function Amp\Socket\connect;
use function Amp\Socket\connectTls;

use Amp\Future;
use Amp\Sync\LocalMutex;
use Amp\Sync\LocalParcel;
use function Amp\async;
use Amp\Log\StreamHandler;

use WebSocket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SocketController extends Controller {
    private $ip = '192.168.0.220';
    private $port = 2888;

    public function change_brightness(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;

        $bright_val = $request['bright'];
        // $auto = $request['auto'];
        // Data to be sent
        if(strlen(dechex($bright_val)) == 1) {
            $bright_val = str_pad(dechex($bright_val), 2, '0', STR_PAD_LEFT);
        }
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0209') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            hex2bin('00') .        // byte 00H - Default is 0
            hex2bin($bright_val) .        // byte 00H - Brigthness level 01=dim 1Fh=bright

        // Calculate the Exclusive of all bytes in $Data1 and 7Fh
        // Calculate XOR checksum of each byte with 0x7F
        $eccValue = 0x7F;
        $length = strlen($data1);
        // echo $data1. 'data1';
        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }


        // Display the original data, ECC value, and the new data
        // echo "<br>ECC Value: " . bin2hex($eccValue) . "\n";
        // echo "<br>Single Byte ECC Value: " . bin2hex(chr($eccValue)) ;
        // echo "----------------------" ;
        // echo "<br><br>Command Packet ... Then Command Packet with ECC appended: <br>" ;
        // echo bin2hex($data1) . "<br>";

        // Create a new variable $data by concatenating $data1 and $eccValue
        $data = $data1 . chr($eccValue);
        // echo bin2hex($data) . "\n <br><br>";


        // Open a socket connection
        $response = [];
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }

        // Send the data

        // echo ". . . sending. . \n";
        // echo $data ;

        // echo "Server  says :";//.$result;
        // print_r($result);

        $res = socket_write($socket, $data, strlen($data));

        // Reply
        $reply = socket_read($socket, 1024);
        // echo "Server Response: ". $reply;

        // echo "<br><br>Reply: " . bin2hex($reply) . "\n <br><br>";
        // echo "-----------------------------" ;


        // $reply = '';
        // socket_set_nonblock($socket);

        // socket_recv($socket, $reply, 1024, 0);

        // echo "<br><br>Reply: " . bin2hex($reply) . "\n <br><br>";
        // echo "-----------------------------" ;

        // Close the connection
        socket_close($socket);

        // echo "<br><br>Connection closed\n";
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        return $response;
    }
    public function TomTest (Request $request) {
        echo '123';
        return 'Testing';
    }
    
    public function get_brightness(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('020A') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            // hex2bin('00') . 
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);

        $currentBright = bin2hex($reply);
        $birghtness = hexdec(substr($currentBright, -6, 2));
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        $response['bright'] = $birghtness;
        return $response;
    }
    // (0101H) Send picture playlist
    public function send_img_playlist(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        // $auto = $request['auto'];
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0101') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            hex2bin($request['page_num']) . // The display page number of the 0th scene, the value range is 0-719, where 0-699 is the user-writable di
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // The dwell time unit of Act 0 is 10 millis
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0003') . // The display page number of Act 1, the value range is 0-
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // The dwell time unit of Act 1 is 10 milliseconds, and the value
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // Reserved, default is 0
            hex2bin('0001') . // Reserved, default is 0
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);
        
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        return $response;
    }
    public function compress_image(Request $request) {
        // Compress Image
        $contents = File::get(base_path().'/public/images/99.bmp');
        // $contents = base64_encode($contents);
        // $manager = new ImageManager(new Driver());

        // // // read image from file system
        // $image = $manager->read(base_path().'/public/images/99.bmp');

        // // resize image proportionally to 300px width
        // $image->scale($request['width'], $request['height']);
        // // insert watermark
        // // $image->place('images/watermark.png');

        // // save modified image in new format 
        // $image->toPng()->save('images/foo.png');
        // $encoded = $image->toJpg();
        // $contents = $encoded;

        $base64 = base64_encode($contents);
        // $hexData = bin2hex($base64);
        $hexData = [3,66,77,182,1,0,0,96,0,0,1,54,0,0,0,40,0,0,0,16,0,0,0,8,0,0,0,1,0,24,120,2,4,0,128,1,0,0,195,14,172,0,128,2,51,19,0,255,255,255,188,2,48,35,0,0,0,0,39,80,0,172,4,42,68,0,51,104,0,51,80,0,172,7,51,104,0,48,80,0,176,5,51,21,0,255,128,0,51,104,0,51,80,0,152,5,212,27,39,128,0,39,196,1,39,32,0,42,104,0,51,80,0,160,4,57,104,0,160,4,180,0,39,152,0,39,32,0,9,0,0,0,0,0,0,0,0,0,0,0,0,17,0,0];
        $data = '';
        for($i = 0; $i < count($hexData); $i++) {
            // echo dechex($hexData[$i]).",";
            $data .= dechex($hexData[$i]);
        }
        $hexData = $data;
        $length = strlen($hexData);
        // $length = 6 + $length;
        $length = 200;
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;

        echo "Opening connection. . .\n<br>";
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0103') .      // dword 020AH - Command Word
            hex2bin($length) .      // dword 0004H - Data Packet Length ('4 bytes')
            hex2bin($request['page_num']) .        // byte 00H - Brigthtness Control 0=auto, 1=manual
            hex2bin($request['width']) . 
            hex2bin($request['height']) .        // byte 00H - Brigthtness Control 0=auto, 1=manual
            // hex2bin($hexData) . 
            hex2bin($hexData) .

        // Calculate the Exclusive of all bytes in $Data1 and 7Fh
        // Calculate XOR checksum of each byte with 0x7F
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }


        // Display the original data, ECC value, and the new data
        // echo "<br>ECC Value: " . bin2hex($eccValue) . "\n";
        // echo "<br>Single Byte ECC Value: " . bin2hex(chr($eccValue)) ;
        // echo "----------------------" ;
        // echo "<br><br>Command Packet ... Then Command Packet with ECC appended: <br>" ;
        // echo bin2hex($data1) . "<br>";

        // Create a new variable $data by concatenating $data1 and $eccValue
        $data = $data1 . chr($eccValue);
        // echo bin2hex($data) . "\n <br><br>";


        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }

        // Send the data

        // echo ". . . sending. . \n";
        // echo $data ;

        // echo "Server  says :";//.$result;
        // print_r($result);

        $res = socket_write($socket, $data, strlen($data));
        
        // return $res;


        // Reply
        $reply = socket_read($socket, 1024);
        // echo "Server Response: ". $reply;

        // echo "<br><br>Reply: " . bin2hex($reply) . "\n <br><br>";
        // echo "-----------------------------" ;


        // $reply = '';
        // socket_set_nonblock($socket);

        // socket_recv($socket, $reply, 1024, 0);

        // echo "<br><br>Reply: " . bin2hex($reply) . "\n <br><br>";
        // echo "-----------------------------" ;


        // Close the connection
        socket_close($socket);

        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        return $response;
    }

    // (0105H) Switch display mode
    public function swith_mode(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        if(!isset($request['mode'])) {
            return "Please add mode in your request body";
        }
        $mode = $request['mode'];
        // $auto = $request['auto'];
        // Data to be sent
        if(strlen(dechex($mode)) == 1) {
            $mode = str_pad(dechex($mode), 2, '0', STR_PAD_LEFT);
        }
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0105') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            hex2bin($mode) . 
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);
        
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        return $response;
    }

    // (0106H) Query the current play screen number
    public function get_current_number(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0106') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            // hex2bin('00') . 
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);

        $currentBright = bin2hex($reply);
        $birghtness = hexdec(substr($currentBright, -6, 4));
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        $response['number'] = $birghtness;
        return $response;
    }
    // (0107H) Query the play picture data of the specified scene number (picture data package read back)
    public function send_picture_specific_screen(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Paramenter
        $pageNum = $request['pageNum'];
        $contractNum = $request['contractNum'];
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0107') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            hex2bin($pageNum) . 
            hex2bin($contractNum) . 
            // hex2bin('00') . 
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);

        $currentBright = bin2hex($reply);
        $birghtness = hexdec(substr($currentBright, -6));
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        $response['number'] = $birghtness;
        return $response;
    }
    // (0109H) Read back the current playlist
    public function get_current_playlist(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('0109') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        return $response;
    }
    // (020DH) Read hardware information height and width
    public function read_information(Request $request) {
        // IP address and port
        $ip = $this->ip;
        $port = $this->port;
        // Data to be sent
        $data1 =hex2bin('5948') .      // dword 5948H - Command Header
            hex2bin('0101') .      // dword 0101H - Address Word
            hex2bin('020D') .      // dword 020AH - Command Word
            hex2bin('0002') .      // dword 0004H - Data Packet Length ('4 bytes')
            // hex2bin('00') . 
        $eccValue = 0x7F;
        $length = strlen($data1);

        for ($i = 0; $i < $length; $i++) {
            $byte = ord($data1[$i]); // Get the ASCII value of the byte
            $eccValue ^= $byte; // XOR with the current byte
        }
        $data = $data1 . chr($eccValue);
        // Open a socket connection
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $response = [];
        if ($socket === false) {
            $response['success'] = false;
            $response['message'] = "Socket creation failed";
            return $response;
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
        }

        // Connect to the server
        $result = socket_connect($socket, $ip, $port);
        if ($result === false) {
            $response['success'] = false;
            $response['message'] = "Socket connection failed";
            return $response;
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($socket)) . "\n";
        }
        $res = socket_write($socket, $data, strlen($data));


        // Reply
        $reply = socket_read($socket, 1024);
        // Close the connection
        socket_close($socket);

        $currentBright = bin2hex($reply);
        $width = hexdec(substr($currentBright, -10, 4));
        $height = hexdec(substr($currentBright, -6, 4));
        $response['success'] = true;
        $response['result'] = bin2hex($reply);
        $response['width'] = $width;
        $response['height'] = $height;
        return $response;
    }
}
